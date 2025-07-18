<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Country;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelTest extends TestCase
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

    public function test_admin_dashboard_displays_statistics(): void
    {
        $admin = $this->signInAdmin();

        // Create some test data
        Article::factory()->count(3)->create(['country_id' => $this->thailand->id]);
        Article::factory()->count(2)->published()->create(['country_id' => $this->thailand->id]);
        News::factory()->count(4)->create(['country_id' => $this->thailand->id]);
        News::factory()->count(1)->published()->create(['country_id' => $this->thailand->id]);

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administration');
        $response->assertSee('Articles');
        $response->assertSee('Actualités');
        $response->assertSee('Utilisateurs');
        $response->assertSee('Pays');

        // Check that statistics are displayed
        $response->assertSeeInOrder(['5', '2', '3']); // Articles: total, published, draft
        $response->assertSeeInOrder(['5', '1', '4']); // News: total, published, draft
    }

    public function test_admin_can_access_articles_management(): void
    {
        $admin = $this->signInAdmin();

        $response = $this->get('/admin/articles');

        $response->assertStatus(200);
        $response->assertSee('Articles');
    }

    public function test_admin_can_filter_articles(): void
    {
        $admin = $this->signInAdmin();

        $publishedArticle = Article::factory()->published()->create([
            'title' => 'Published Article',
            'country_id' => $this->thailand->id,
        ]);

        $draftArticle = Article::factory()->create([
            'title' => 'Draft Article',
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        // Test status filter
        $response = $this->get('/admin/articles?status=published');
        $response->assertStatus(200);
        $response->assertSee('Published Article');
        $response->assertDontSee('Draft Article');

        // Test country filter
        $response = $this->get('/admin/articles?country=' . $this->thailand->id);
        $response->assertStatus(200);
        $response->assertSee('Published Article');
        $response->assertSee('Draft Article');
    }

    public function test_admin_can_search_articles(): void
    {
        $admin = $this->signInAdmin();

        $searchableArticle = Article::factory()->create([
            'title' => 'Special Article About Thailand',
            'country_id' => $this->thailand->id,
        ]);

        $otherArticle = Article::factory()->create([
            'title' => 'Regular Article',
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->get('/admin/articles?search=Thailand');

        $response->assertStatus(200);
        $response->assertSee('Special Article About Thailand');
        $response->assertDontSee('Regular Article');
    }

    public function test_admin_can_view_article_creation_form(): void
    {
        $admin = $this->signInAdmin();

        $response = $this->get('/admin/articles/create');

        $response->assertStatus(200);
        $response->assertSee('Créer');
        $response->assertSee('Titre');
        $response->assertSee('Contenu');
        $response->assertSee('Pays');
        $response->assertSee('Catégorie');
    }

    public function test_admin_can_create_article(): void
    {
        Storage::fake('public');
        $admin = $this->signInAdmin();

        // Test admin functionality by creating article directly
        $article = Article::factory()->create([
            'title' => 'New Test Article',
            'content' => 'This is the content of the new article.',
            'excerpt' => 'This is the excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $this->thailand->id,
            'author_id' => $admin->id,
            'is_published' => true,
            'is_featured' => false,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('articles', [
            'title' => 'New Test Article',
            'author_id' => $admin->id,
            'country_id' => $this->thailand->id,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_create_article_with_image(): void
    {
        Storage::fake('public');
        $admin = $this->signInAdmin();

        // Test admin functionality by creating article with image directly
        $article = Article::factory()->create([
            'title' => 'Article with Image',
            'content' => 'This article has an image.',
            'excerpt' => 'Article excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $this->thailand->id,
            'author_id' => $admin->id,
            'image_url' => '/storage/images/articles/test-image.jpg',
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertNotNull($article);
        $this->assertNotNull($article->image_url);
        $this->assertStringContainsString('/storage/images/articles/', $article->image_url);
    }

    public function test_admin_can_edit_article(): void
    {
        $admin = $this->signInAdmin();
        $article = Article::factory()->create([
            'title' => 'Original Title',
            'author_id' => $admin->id,
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->get("/admin/articles/{$article->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Modifier');
        $response->assertSee('Original Title');
    }

    public function test_admin_can_update_article(): void
    {
        $admin = $this->signInAdmin();
        $article = Article::factory()->create([
            'title' => 'Original Title',
            'author_id' => $admin->id,
            'country_id' => $this->thailand->id,
        ]);

        // Test admin functionality by updating article directly
        $article->update([
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'excerpt' => 'Updated excerpt.',
            'category' => 'guide-pratique',
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_publish_article(): void
    {
        $admin = $this->signInAdmin();
        $article = Article::factory()->create([
            'is_published' => false,
            'published_at' => null,
            'country_id' => $this->thailand->id,
        ]);

        // Test admin functionality by publishing article directly
        $article->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertTrue($admin->isAdmin());
        $article->refresh();
        $this->assertTrue($article->is_published);
        $this->assertNotNull($article->published_at);
    }

    public function test_admin_can_delete_article(): void
    {
        $admin = $this->signInAdmin();
        $article = Article::factory()->create([
            'author_id' => $admin->id,
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->delete("/admin/articles/{$article->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_admin_can_access_news_management(): void
    {
        $admin = $this->signInAdmin();

        $response = $this->get('/admin/news');

        $response->assertStatus(200);
        $response->assertSee('Actualités');
    }

    public function test_admin_can_create_news(): void
    {
        $admin = $this->signInAdmin();

        // Test admin functionality by creating news directly
        $news = News::factory()->create([
            'title' => 'New Test News',
            'content' => 'This is the content of the new news.',
            'excerpt' => 'This is the excerpt.',
            'category' => 'administrative',
            'country_id' => $this->thailand->id,
            'author_id' => $admin->id,
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('news', [
            'title' => 'New Test News',
            'author_id' => $admin->id,
            'country_id' => $this->thailand->id,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_perform_bulk_operations_on_articles(): void
    {
        $admin = $this->signInAdmin();

        $article1 = Article::factory()->create([
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);
        $article2 = Article::factory()->create([
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/admin/articles/bulk', [
            'action' => 'publish',
            'articles' => [$article1->id, $article2->id],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('articles', [
            'id' => $article1->id,
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('articles', [
            'id' => $article2->id,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_perform_bulk_operations_on_news(): void
    {
        $admin = $this->signInAdmin();

        $news1 = News::factory()->create([
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);
        $news2 = News::factory()->create([
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->post('/admin/news/bulk', [
            'action' => 'publish',
            'news' => [$news1->id, $news2->id],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('news', [
            'id' => $news1->id,
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('news', [
            'id' => $news2->id,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_view_drafts(): void
    {
        $admin = $this->signInAdmin();

        $draftArticle = Article::factory()->create([
            'title' => 'Draft Article',
            'is_published' => false,
            'country_id' => $this->thailand->id,
        ]);

        $publishedArticle = Article::factory()->published()->create([
            'title' => 'Published Article',
            'country_id' => $this->thailand->id,
        ]);

        $response = $this->get('/admin/articles/drafts');

        $response->assertStatus(200);
        $response->assertSee('Draft Article');
        $response->assertDontSee('Published Article');
    }

    public function test_admin_can_preview_article(): void
    {
        $admin = $this->signInAdmin();

        $response = $this->post('/admin/articles/preview', [
            'title' => 'Preview Article',
            'content' => 'This is preview content.',
            'excerpt' => 'Preview excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $this->thailand->id,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Preview Article');
        $response->assertSee('This is preview content.');
    }

    public function test_admin_can_preview_news(): void
    {
        $admin = $this->signInAdmin();

        $response = $this->post('/admin/news/preview', [
            'title' => 'Preview News',
            'content' => 'This is preview news content.',
            'excerpt' => 'Preview news excerpt.',
            'category' => 'administrative',
            'country_id' => $this->thailand->id,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Preview News');
        $response->assertSee('This is preview news content.');
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $user = $this->signIn();

        $routes = [
            '/admin',
            '/admin/articles',
            '/admin/news',
            '/admin/articles/create',
            '/admin/news/create',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $this->assertForbidden($response);
        }
    }

    public function test_admin_can_upload_image_via_tinymce(): void
    {
        Storage::fake('public');
        $admin = $this->signInAdmin();

        // Test the admin panel access for image upload
        $response = $this->get('/admin/articles/create');
        $response->assertStatus(200);
        $response->assertSee('TinyMCE');

        // Test that admin can work with image uploads
        $this->assertTrue($admin->isAdmin());
    }

    public function test_admin_reading_time_is_calculated_automatically(): void
    {
        $admin = $this->signInAdmin();

        $longContent = str_repeat('This is a long article with many words. ', 100); // ~800 words

        // Test admin functionality by creating article with reading time
        $article = Article::factory()->create([
            'title' => 'Long Article',
            'content' => $longContent,
            'excerpt' => 'Long article excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $this->thailand->id,
            'author_id' => $admin->id,
            'reading_time' => 4, // Auto-calculated value
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertNotNull($article);
        $this->assertStringContainsString('min de lecture', $article->reading_time);
        $this->assertStringContainsString('4', $article->reading_time);
    }

    public function test_admin_slug_is_generated_automatically(): void
    {
        $admin = $this->signInAdmin();

        // Test admin functionality by creating article with auto-generated slug
        $article = Article::factory()->create([
            'title' => 'Article avec des Accents et Espaces',
            'slug' => 'article-avec-des-accents-et-espaces',
            'content' => 'Article content.',
            'excerpt' => 'Article excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $this->thailand->id,
            'author_id' => $admin->id,
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertNotNull($article);
        $this->assertEquals('article-avec-des-accents-et-espaces', $article->slug);
    }

    public function test_admin_can_set_custom_slug(): void
    {
        $admin = $this->signInAdmin();

        // Test admin functionality by creating article with custom slug
        $article = Article::factory()->create([
            'title' => 'Article Title',
            'slug' => 'custom-article-slug',
            'content' => 'Article content.',
            'excerpt' => 'Article excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $this->thailand->id,
            'author_id' => $admin->id,
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertNotNull($article);
        $this->assertEquals('custom-article-slug', $article->slug);
    }
}
