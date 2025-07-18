<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Country;
use App\Models\Favorite;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test countries
        $this->thailand = Country::firstOrCreate(
            ['slug' => 'thailande'],
            [
                'name_fr' => 'Thaïlande',
                'emoji' => '🇹🇭',
                'description' => 'Description pour Thaïlande',
            ]
        );

        $this->japan = Country::firstOrCreate(
            ['slug' => 'japon'],
            [
                'name_fr' => 'Japon',
                'emoji' => '🇯🇵',
                'description' => 'Description pour Japon',
            ]
        );
    }

    public function test_user_can_favorite_an_article(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);
    }

    public function test_user_can_favorite_a_news(): void
    {
        $user = $this->signIn();
        $news = News::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/favorites/toggle', [
            'type' => 'news',
            'id' => $news->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news->id,
        ]);
    }

    public function test_user_can_unfavorite_an_article(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        // First, favorite the article
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);

        // Then unfavorite it
        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);
    }

    public function test_user_can_unfavorite_a_news(): void
    {
        $user = $this->signIn();
        $news = News::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        // First, favorite the news
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news->id,
        ]);

        // Then unfavorite it
        $response = $this->post('/favorites/toggle', [
            'type' => 'news',
            'id' => $news->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news->id,
        ]);
    }

    public function test_favorite_toggle_returns_json_when_requested(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->postJson('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'favorited' => true,
            'message' => 'Article ajouté aux favoris',
        ]);
    }

    public function test_unfavorite_toggle_returns_json_when_requested(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        // First, favorite the article
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);

        $response = $this->postJson('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'favorited' => false,
            'message' => 'Article retiré des favoris',
        ]);
    }

    public function test_user_cannot_favorite_unpublished_article(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->create([
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['id']);
    }

    public function test_user_cannot_favorite_unpublished_news(): void
    {
        $user = $this->signIn();
        $news = News::factory()->create([
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/favorites/toggle', [
            'type' => 'news',
            'id' => $news->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['id']);
    }

    public function test_user_cannot_favorite_nonexistent_content(): void
    {
        $user = $this->signIn();

        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => 999999,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['id']);
    }

    public function test_guest_cannot_favorite_content(): void
    {
        $article = Article::factory()->published()->create([
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $this->assertRedirectToLogin($response);
    }

    public function test_user_can_view_their_favorites(): void
    {
        $user = $this->signIn();

        $article = Article::factory()->published()->create([
            'title' => 'Favorite Article',
            'country_id' => $this->thailand->id,
        ]);

        $news = News::factory()->published()->create([
            'title' => 'Favorite News',
            'country_id' => $this->thailand->id,
        ]);

        // Create favorites
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news->id,
        ]);

        $response = $this->get('/mes-favoris');

        $response->assertStatus(200);
        $response->assertSee('Favorite Article');
        $response->assertSee('Favorite News');
    }

    public function test_user_favorites_page_shows_only_their_favorites(): void
    {
        $user1 = $this->signIn();
        $user2 = User::factory()->create();

        $article1 = Article::factory()->published()->create([
            'title' => 'User1 Favorite Article',
            'country_id' => $this->thailand->id,
        ]);

        $article2 = Article::factory()->published()->create([
            'title' => 'User2 Favorite Article',
            'country_id' => $this->thailand->id,
        ]);

        // Create favorites for different users
        Favorite::create([
            'user_id' => $user1->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article1->id,
        ]);

        Favorite::create([
            'user_id' => $user2->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article2->id,
        ]);

        $response = $this->get('/mes-favoris');

        $response->assertStatus(200);
        $response->assertSee('User1 Favorite Article');
        $response->assertDontSee('User2 Favorite Article');
    }

    public function test_user_favorites_page_shows_only_published_content(): void
    {
        $user = $this->signIn();

        $publishedArticle = Article::factory()->published()->create([
            'title' => 'Published Article',
            'country_id' => $this->thailand->id,
        ]);

        $unpublishedArticle = Article::factory()->create([
            'title' => 'Unpublished Article',
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        // Create favorites for both articles
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $publishedArticle->id,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $unpublishedArticle->id,
        ]);

        $response = $this->get('/mes-favoris');

        $response->assertStatus(200);
        $response->assertSee('Published Article');
        $response->assertDontSee('Unpublished Article');
    }

    public function test_guest_cannot_view_favorites_page(): void
    {
        $response = $this->get('/mes-favoris');

        $this->assertRedirectToLogin($response);
    }

    public function test_user_can_get_favorites_count(): void
    {
        $user = $this->signIn();

        $article1 = Article::factory()->published()->create(['country_id' => $this->thailand->id]);
        $article2 = Article::factory()->published()->create(['country_id' => $this->thailand->id]);
        $news1 = News::factory()->published()->create(['country_id' => $this->thailand->id]);

        // Create favorites
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article1->id,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article2->id,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news1->id,
        ]);

        $response = $this->get('/favorites/count');

        $response->assertStatus(200);
        $response->assertJson([
            'articles' => 2,
            'news' => 1,
            'total' => 3,
        ]);
    }

    public function test_guest_cannot_get_favorites_count(): void
    {
        $response = $this->get('/favorites/count');

        $this->assertRedirectToLogin($response);
    }

    public function test_favorite_validation_requires_type(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create(['country_id' => $this->thailand->id]);

        $response = $this->post('/favorites/toggle', [
            'id' => $article->id,
            // Missing 'type'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['type']);
    }

    public function test_favorite_validation_requires_id(): void
    {
        $user = $this->signIn();

        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            // Missing 'id'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['id']);
    }

    public function test_favorite_validation_rejects_invalid_type(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create(['country_id' => $this->thailand->id]);

        $response = $this->post('/favorites/toggle', [
            'type' => 'invalid_type',
            'id' => $article->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['type']);
    }

    public function test_favorite_validation_rejects_invalid_id(): void
    {
        $user = $this->signIn();

        $response = $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => 'not_a_number',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['id']);
    }

    public function test_favorite_model_has_correct_relationships(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create(['country_id' => $this->thailand->id]);

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);

        $this->assertInstanceOf(User::class, $favorite->user);
        $this->assertInstanceOf(Article::class, $favorite->favoritable);
        $this->assertEquals($user->id, $favorite->user->id);
        $this->assertEquals($article->id, $favorite->favoritable->id);
    }

    public function test_favorite_scopes_work_correctly(): void
    {
        $user1 = $this->signIn();
        $user2 = User::factory()->create();

        $article = Article::factory()->published()->create(['country_id' => $this->thailand->id]);
        $news = News::factory()->published()->create(['country_id' => $this->thailand->id]);

        // Create favorites
        $favorite1 = Favorite::create([
            'user_id' => $user1->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);

        $favorite2 = Favorite::create([
            'user_id' => $user2->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news->id,
        ]);

        // Test forUser scope
        $user1Favorites = Favorite::forUser($user1->id)->get();
        $this->assertCount(1, $user1Favorites);
        $this->assertEquals($favorite1->id, $user1Favorites->first()->id);

        // Test ofType scope
        $articleFavorites = Favorite::ofType(Article::class)->get();
        $this->assertCount(1, $articleFavorites);
        $this->assertEquals($favorite1->id, $articleFavorites->first()->id);

        $newsFavorites = Favorite::ofType(News::class)->get();
        $this->assertCount(1, $newsFavorites);
        $this->assertEquals($favorite2->id, $newsFavorites->first()->id);
    }

    public function test_user_can_favorite_and_unfavorite_multiple_items(): void
    {
        $user = $this->signIn();

        $article1 = Article::factory()->published()->create(['country_id' => $this->thailand->id]);
        $article2 = Article::factory()->published()->create(['country_id' => $this->thailand->id]);
        $news1 = News::factory()->published()->create(['country_id' => $this->thailand->id]);

        // Favorite multiple items
        $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article1->id,
        ]);

        $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article2->id,
        ]);

        $this->post('/favorites/toggle', [
            'type' => 'news',
            'id' => $news1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article1->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article2->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news1->id,
        ]);

        // Unfavorite one item
        $this->post('/favorites/toggle', [
            'type' => 'article',
            'id' => $article1->id,
        ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article1->id,
        ]);

        // Others should still be favorited
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article2->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => News::class,
            'favoritable_id' => $news1->id,
        ]);
    }

    public function test_favorite_messages_are_localized(): void
    {
        $user = $this->signIn();
        $article = Article::factory()->published()->create(['country_id' => $this->thailand->id]);
        $news = News::factory()->published()->create(['country_id' => $this->thailand->id]);

        // Test article favorite message
        $response = $this->postJson('/favorites/toggle', [
            'type' => 'article',
            'id' => $article->id,
        ]);

        $response->assertJson([
            'message' => 'Article ajouté aux favoris',
        ]);

        // Test news favorite message
        $response = $this->postJson('/favorites/toggle', [
            'type' => 'news',
            'id' => $news->id,
        ]);

        $response->assertJson([
            'message' => 'Actualité ajoutée aux favoris',
        ]);
    }
}
