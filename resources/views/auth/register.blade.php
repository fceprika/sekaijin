@extends('layout')

@section('title', 'Inscription - Sekaijin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Rejoindre Sekaijin</h1>
            <p class="text-gray-600">Connectez-vous avec la communauté des expatriés français</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            
            <!-- Pseudo -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Pseudo *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Choisissez votre pseudo">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            <!-- Pays de résidence -->
            <div>
                <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence *</label>
                <select id="country_residence" name="country_residence" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="">Sélectionnez un pays</option>
                    
                    <!-- Afrique -->
                    <option value="Afrique du Sud" {{ old('country_residence') == 'Afrique du Sud' ? 'selected' : '' }}>Afrique du Sud</option>
                    <option value="Algérie" {{ old('country_residence') == 'Algérie' ? 'selected' : '' }}>Algérie</option>
                    <option value="Angola" {{ old('country_residence') == 'Angola' ? 'selected' : '' }}>Angola</option>
                    <option value="Bénin" {{ old('country_residence') == 'Bénin' ? 'selected' : '' }}>Bénin</option>
                    <option value="Botswana" {{ old('country_residence') == 'Botswana' ? 'selected' : '' }}>Botswana</option>
                    <option value="Burkina Faso" {{ old('country_residence') == 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                    <option value="Burundi" {{ old('country_residence') == 'Burundi' ? 'selected' : '' }}>Burundi</option>
                    <option value="Cameroun" {{ old('country_residence') == 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                    <option value="Cap-Vert" {{ old('country_residence') == 'Cap-Vert' ? 'selected' : '' }}>Cap-Vert</option>
                    <option value="Centrafrique" {{ old('country_residence') == 'Centrafrique' ? 'selected' : '' }}>Centrafrique</option>
                    <option value="Comores" {{ old('country_residence') == 'Comores' ? 'selected' : '' }}>Comores</option>
                    <option value="Congo" {{ old('country_residence') == 'Congo' ? 'selected' : '' }}>Congo</option>
                    <option value="Congo (RDC)" {{ old('country_residence') == 'Congo (RDC)' ? 'selected' : '' }}>Congo (RDC)</option>
                    <option value="Côte d'Ivoire" {{ old('country_residence') == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                    <option value="Djibouti" {{ old('country_residence') == 'Djibouti' ? 'selected' : '' }}>Djibouti</option>
                    <option value="Égypte" {{ old('country_residence') == 'Égypte' ? 'selected' : '' }}>Égypte</option>
                    <option value="Érythrée" {{ old('country_residence') == 'Érythrée' ? 'selected' : '' }}>Érythrée</option>
                    <option value="Eswatini" {{ old('country_residence') == 'Eswatini' ? 'selected' : '' }}>Eswatini</option>
                    <option value="Éthiopie" {{ old('country_residence') == 'Éthiopie' ? 'selected' : '' }}>Éthiopie</option>
                    <option value="Gabon" {{ old('country_residence') == 'Gabon' ? 'selected' : '' }}>Gabon</option>
                    <option value="Gambie" {{ old('country_residence') == 'Gambie' ? 'selected' : '' }}>Gambie</option>
                    <option value="Ghana" {{ old('country_residence') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                    <option value="Guinée" {{ old('country_residence') == 'Guinée' ? 'selected' : '' }}>Guinée</option>
                    <option value="Guinée-Bissau" {{ old('country_residence') == 'Guinée-Bissau' ? 'selected' : '' }}>Guinée-Bissau</option>
                    <option value="Guinée équatoriale" {{ old('country_residence') == 'Guinée équatoriale' ? 'selected' : '' }}>Guinée équatoriale</option>
                    <option value="Kenya" {{ old('country_residence') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                    <option value="Lesotho" {{ old('country_residence') == 'Lesotho' ? 'selected' : '' }}>Lesotho</option>
                    <option value="Libéria" {{ old('country_residence') == 'Libéria' ? 'selected' : '' }}>Libéria</option>
                    <option value="Libye" {{ old('country_residence') == 'Libye' ? 'selected' : '' }}>Libye</option>
                    <option value="Madagascar" {{ old('country_residence') == 'Madagascar' ? 'selected' : '' }}>Madagascar</option>
                    <option value="Malawi" {{ old('country_residence') == 'Malawi' ? 'selected' : '' }}>Malawi</option>
                    <option value="Mali" {{ old('country_residence') == 'Mali' ? 'selected' : '' }}>Mali</option>
                    <option value="Maroc" {{ old('country_residence') == 'Maroc' ? 'selected' : '' }}>Maroc</option>
                    <option value="Maurice" {{ old('country_residence') == 'Maurice' ? 'selected' : '' }}>Maurice</option>
                    <option value="Mauritanie" {{ old('country_residence') == 'Mauritanie' ? 'selected' : '' }}>Mauritanie</option>
                    <option value="Mozambique" {{ old('country_residence') == 'Mozambique' ? 'selected' : '' }}>Mozambique</option>
                    <option value="Namibie" {{ old('country_residence') == 'Namibie' ? 'selected' : '' }}>Namibie</option>
                    <option value="Niger" {{ old('country_residence') == 'Niger' ? 'selected' : '' }}>Niger</option>
                    <option value="Nigéria" {{ old('country_residence') == 'Nigéria' ? 'selected' : '' }}>Nigéria</option>
                    <option value="Ouganda" {{ old('country_residence') == 'Ouganda' ? 'selected' : '' }}>Ouganda</option>
                    <option value="Rwanda" {{ old('country_residence') == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                    <option value="Sao Tomé-et-Principe" {{ old('country_residence') == 'Sao Tomé-et-Principe' ? 'selected' : '' }}>Sao Tomé-et-Principe</option>
                    <option value="Sénégal" {{ old('country_residence') == 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                    <option value="Seychelles" {{ old('country_residence') == 'Seychelles' ? 'selected' : '' }}>Seychelles</option>
                    <option value="Sierra Leone" {{ old('country_residence') == 'Sierra Leone' ? 'selected' : '' }}>Sierra Leone</option>
                    <option value="Somalie" {{ old('country_residence') == 'Somalie' ? 'selected' : '' }}>Somalie</option>
                    <option value="Soudan" {{ old('country_residence') == 'Soudan' ? 'selected' : '' }}>Soudan</option>
                    <option value="Soudan du Sud" {{ old('country_residence') == 'Soudan du Sud' ? 'selected' : '' }}>Soudan du Sud</option>
                    <option value="Tanzanie" {{ old('country_residence') == 'Tanzanie' ? 'selected' : '' }}>Tanzanie</option>
                    <option value="Tchad" {{ old('country_residence') == 'Tchad' ? 'selected' : '' }}>Tchad</option>
                    <option value="Togo" {{ old('country_residence') == 'Togo' ? 'selected' : '' }}>Togo</option>
                    <option value="Tunisie" {{ old('country_residence') == 'Tunisie' ? 'selected' : '' }}>Tunisie</option>
                    <option value="Zambie" {{ old('country_residence') == 'Zambie' ? 'selected' : '' }}>Zambie</option>
                    <option value="Zimbabwe" {{ old('country_residence') == 'Zimbabwe' ? 'selected' : '' }}>Zimbabwe</option>

                    <!-- Amérique du Nord -->
                    <option value="Canada" {{ old('country_residence') == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="États-Unis" {{ old('country_residence') == 'États-Unis' ? 'selected' : '' }}>États-Unis</option>
                    <option value="Mexique" {{ old('country_residence') == 'Mexique' ? 'selected' : '' }}>Mexique</option>

                    <!-- Amérique centrale -->
                    <option value="Belize" {{ old('country_residence') == 'Belize' ? 'selected' : '' }}>Belize</option>
                    <option value="Costa Rica" {{ old('country_residence') == 'Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
                    <option value="Guatemala" {{ old('country_residence') == 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
                    <option value="Honduras" {{ old('country_residence') == 'Honduras' ? 'selected' : '' }}>Honduras</option>
                    <option value="Nicaragua" {{ old('country_residence') == 'Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
                    <option value="Panama" {{ old('country_residence') == 'Panama' ? 'selected' : '' }}>Panama</option>
                    <option value="Salvador" {{ old('country_residence') == 'Salvador' ? 'selected' : '' }}>Salvador</option>

                    <!-- Amérique du Sud -->
                    <option value="Argentine" {{ old('country_residence') == 'Argentine' ? 'selected' : '' }}>Argentine</option>
                    <option value="Bolivie" {{ old('country_residence') == 'Bolivie' ? 'selected' : '' }}>Bolivie</option>
                    <option value="Brésil" {{ old('country_residence') == 'Brésil' ? 'selected' : '' }}>Brésil</option>
                    <option value="Chili" {{ old('country_residence') == 'Chili' ? 'selected' : '' }}>Chili</option>
                    <option value="Colombie" {{ old('country_residence') == 'Colombie' ? 'selected' : '' }}>Colombie</option>
                    <option value="Équateur" {{ old('country_residence') == 'Équateur' ? 'selected' : '' }}>Équateur</option>
                    <option value="Guyana" {{ old('country_residence') == 'Guyana' ? 'selected' : '' }}>Guyana</option>
                    <option value="Paraguay" {{ old('country_residence') == 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
                    <option value="Pérou" {{ old('country_residence') == 'Pérou' ? 'selected' : '' }}>Pérou</option>
                    <option value="Suriname" {{ old('country_residence') == 'Suriname' ? 'selected' : '' }}>Suriname</option>
                    <option value="Uruguay" {{ old('country_residence') == 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                    <option value="Venezuela" {{ old('country_residence') == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>

                    <!-- Asie -->
                    <option value="Afghanistan" {{ old('country_residence') == 'Afghanistan' ? 'selected' : '' }}>Afghanistan</option>
                    <option value="Arabie saoudite" {{ old('country_residence') == 'Arabie saoudite' ? 'selected' : '' }}>Arabie saoudite</option>
                    <option value="Arménie" {{ old('country_residence') == 'Arménie' ? 'selected' : '' }}>Arménie</option>
                    <option value="Azerbaïdjan" {{ old('country_residence') == 'Azerbaïdjan' ? 'selected' : '' }}>Azerbaïdjan</option>
                    <option value="Bahreïn" {{ old('country_residence') == 'Bahreïn' ? 'selected' : '' }}>Bahreïn</option>
                    <option value="Bangladesh" {{ old('country_residence') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                    <option value="Bhoutan" {{ old('country_residence') == 'Bhoutan' ? 'selected' : '' }}>Bhoutan</option>
                    <option value="Birmanie (Myanmar)" {{ old('country_residence') == 'Birmanie (Myanmar)' ? 'selected' : '' }}>Birmanie (Myanmar)</option>
                    <option value="Brunei" {{ old('country_residence') == 'Brunei' ? 'selected' : '' }}>Brunei</option>
                    <option value="Cambodge" {{ old('country_residence') == 'Cambodge' ? 'selected' : '' }}>Cambodge</option>
                    <option value="Chine" {{ old('country_residence') == 'Chine' ? 'selected' : '' }}>Chine</option>
                    <option value="Chypre" {{ old('country_residence') == 'Chypre' ? 'selected' : '' }}>Chypre</option>
                    <option value="Corée du Nord" {{ old('country_residence') == 'Corée du Nord' ? 'selected' : '' }}>Corée du Nord</option>
                    <option value="Corée du Sud" {{ old('country_residence') == 'Corée du Sud' ? 'selected' : '' }}>Corée du Sud</option>
                    <option value="Émirats arabes unis" {{ old('country_residence') == 'Émirats arabes unis' ? 'selected' : '' }}>Émirats arabes unis</option>
                    <option value="Géorgie" {{ old('country_residence') == 'Géorgie' ? 'selected' : '' }}>Géorgie</option>
                    <option value="Inde" {{ old('country_residence') == 'Inde' ? 'selected' : '' }}>Inde</option>
                    <option value="Indonésie" {{ old('country_residence') == 'Indonésie' ? 'selected' : '' }}>Indonésie</option>
                    <option value="Irak" {{ old('country_residence') == 'Irak' ? 'selected' : '' }}>Irak</option>
                    <option value="Iran" {{ old('country_residence') == 'Iran' ? 'selected' : '' }}>Iran</option>
                    <option value="Israël" {{ old('country_residence') == 'Israël' ? 'selected' : '' }}>Israël</option>
                    <option value="Japon" {{ old('country_residence') == 'Japon' ? 'selected' : '' }}>Japon</option>
                    <option value="Jordanie" {{ old('country_residence') == 'Jordanie' ? 'selected' : '' }}>Jordanie</option>
                    <option value="Kazakhstan" {{ old('country_residence') == 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
                    <option value="Kirghizistan" {{ old('country_residence') == 'Kirghizistan' ? 'selected' : '' }}>Kirghizistan</option>
                    <option value="Koweït" {{ old('country_residence') == 'Koweït' ? 'selected' : '' }}>Koweït</option>
                    <option value="Laos" {{ old('country_residence') == 'Laos' ? 'selected' : '' }}>Laos</option>
                    <option value="Liban" {{ old('country_residence') == 'Liban' ? 'selected' : '' }}>Liban</option>
                    <option value="Malaisie" {{ old('country_residence') == 'Malaisie' ? 'selected' : '' }}>Malaisie</option>
                    <option value="Maldives" {{ old('country_residence') == 'Maldives' ? 'selected' : '' }}>Maldives</option>
                    <option value="Mongolie" {{ old('country_residence') == 'Mongolie' ? 'selected' : '' }}>Mongolie</option>
                    <option value="Népal" {{ old('country_residence') == 'Népal' ? 'selected' : '' }}>Népal</option>
                    <option value="Oman" {{ old('country_residence') == 'Oman' ? 'selected' : '' }}>Oman</option>
                    <option value="Ouzbékistan" {{ old('country_residence') == 'Ouzbékistan' ? 'selected' : '' }}>Ouzbékistan</option>
                    <option value="Pakistan" {{ old('country_residence') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                    <option value="Palestine" {{ old('country_residence') == 'Palestine' ? 'selected' : '' }}>Palestine</option>
                    <option value="Philippines" {{ old('country_residence') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                    <option value="Qatar" {{ old('country_residence') == 'Qatar' ? 'selected' : '' }}>Qatar</option>
                    <option value="Singapour" {{ old('country_residence') == 'Singapour' ? 'selected' : '' }}>Singapour</option>
                    <option value="Sri Lanka" {{ old('country_residence') == 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                    <option value="Syrie" {{ old('country_residence') == 'Syrie' ? 'selected' : '' }}>Syrie</option>
                    <option value="Tadjikistan" {{ old('country_residence') == 'Tadjikistan' ? 'selected' : '' }}>Tadjikistan</option>
                    <option value="Taïwan" {{ old('country_residence') == 'Taïwan' ? 'selected' : '' }}>Taïwan</option>
                    <option value="Thaïlande" {{ old('country_residence') == 'Thaïlande' ? 'selected' : '' }}>Thaïlande</option>
                    <option value="Timor oriental" {{ old('country_residence') == 'Timor oriental' ? 'selected' : '' }}>Timor oriental</option>
                    <option value="Turkménistan" {{ old('country_residence') == 'Turkménistan' ? 'selected' : '' }}>Turkménistan</option>
                    <option value="Turquie" {{ old('country_residence') == 'Turquie' ? 'selected' : '' }}>Turquie</option>
                    <option value="Viêt Nam" {{ old('country_residence') == 'Viêt Nam' ? 'selected' : '' }}>Viêt Nam</option>
                    <option value="Yémen" {{ old('country_residence') == 'Yémen' ? 'selected' : '' }}>Yémen</option>

                    <!-- Europe -->
                    <option value="Albanie" {{ old('country_residence') == 'Albanie' ? 'selected' : '' }}>Albanie</option>
                    <option value="Allemagne" {{ old('country_residence') == 'Allemagne' ? 'selected' : '' }}>Allemagne</option>
                    <option value="Andorre" {{ old('country_residence') == 'Andorre' ? 'selected' : '' }}>Andorre</option>
                    <option value="Autriche" {{ old('country_residence') == 'Autriche' ? 'selected' : '' }}>Autriche</option>
                    <option value="Belgique" {{ old('country_residence') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                    <option value="Biélorussie" {{ old('country_residence') == 'Biélorussie' ? 'selected' : '' }}>Biélorussie</option>
                    <option value="Bosnie-Herzégovine" {{ old('country_residence') == 'Bosnie-Herzégovine' ? 'selected' : '' }}>Bosnie-Herzégovine</option>
                    <option value="Bulgarie" {{ old('country_residence') == 'Bulgarie' ? 'selected' : '' }}>Bulgarie</option>
                    <option value="Croatie" {{ old('country_residence') == 'Croatie' ? 'selected' : '' }}>Croatie</option>
                    <option value="Danemark" {{ old('country_residence') == 'Danemark' ? 'selected' : '' }}>Danemark</option>
                    <option value="Espagne" {{ old('country_residence') == 'Espagne' ? 'selected' : '' }}>Espagne</option>
                    <option value="Estonie" {{ old('country_residence') == 'Estonie' ? 'selected' : '' }}>Estonie</option>
                    <option value="Finlande" {{ old('country_residence') == 'Finlande' ? 'selected' : '' }}>Finlande</option>
                    <option value="France" {{ old('country_residence') == 'France' ? 'selected' : '' }}>France</option>
                    <option value="Grèce" {{ old('country_residence') == 'Grèce' ? 'selected' : '' }}>Grèce</option>
                    <option value="Hongrie" {{ old('country_residence') == 'Hongrie' ? 'selected' : '' }}>Hongrie</option>
                    <option value="Irlande" {{ old('country_residence') == 'Irlande' ? 'selected' : '' }}>Irlande</option>
                    <option value="Islande" {{ old('country_residence') == 'Islande' ? 'selected' : '' }}>Islande</option>
                    <option value="Italie" {{ old('country_residence') == 'Italie' ? 'selected' : '' }}>Italie</option>
                    <option value="Kosovo" {{ old('country_residence') == 'Kosovo' ? 'selected' : '' }}>Kosovo</option>
                    <option value="Lettonie" {{ old('country_residence') == 'Lettonie' ? 'selected' : '' }}>Lettonie</option>
                    <option value="Liechtenstein" {{ old('country_residence') == 'Liechtenstein' ? 'selected' : '' }}>Liechtenstein</option>
                    <option value="Lituanie" {{ old('country_residence') == 'Lituanie' ? 'selected' : '' }}>Lituanie</option>
                    <option value="Luxembourg" {{ old('country_residence') == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                    <option value="Macédoine du Nord" {{ old('country_residence') == 'Macédoine du Nord' ? 'selected' : '' }}>Macédoine du Nord</option>
                    <option value="Malte" {{ old('country_residence') == 'Malte' ? 'selected' : '' }}>Malte</option>
                    <option value="Moldavie" {{ old('country_residence') == 'Moldavie' ? 'selected' : '' }}>Moldavie</option>
                    <option value="Monaco" {{ old('country_residence') == 'Monaco' ? 'selected' : '' }}>Monaco</option>
                    <option value="Monténégro" {{ old('country_residence') == 'Monténégro' ? 'selected' : '' }}>Monténégro</option>
                    <option value="Norvège" {{ old('country_residence') == 'Norvège' ? 'selected' : '' }}>Norvège</option>
                    <option value="Pays-Bas" {{ old('country_residence') == 'Pays-Bas' ? 'selected' : '' }}>Pays-Bas</option>
                    <option value="Pologne" {{ old('country_residence') == 'Pologne' ? 'selected' : '' }}>Pologne</option>
                    <option value="Portugal" {{ old('country_residence') == 'Portugal' ? 'selected' : '' }}>Portugal</option>
                    <option value="République tchèque" {{ old('country_residence') == 'République tchèque' ? 'selected' : '' }}>République tchèque</option>
                    <option value="Roumanie" {{ old('country_residence') == 'Roumanie' ? 'selected' : '' }}>Roumanie</option>
                    <option value="Royaume-Uni" {{ old('country_residence') == 'Royaume-Uni' ? 'selected' : '' }}>Royaume-Uni</option>
                    <option value="Russie" {{ old('country_residence') == 'Russie' ? 'selected' : '' }}>Russie</option>
                    <option value="Saint-Marin" {{ old('country_residence') == 'Saint-Marin' ? 'selected' : '' }}>Saint-Marin</option>
                    <option value="Serbie" {{ old('country_residence') == 'Serbie' ? 'selected' : '' }}>Serbie</option>
                    <option value="Slovaquie" {{ old('country_residence') == 'Slovaquie' ? 'selected' : '' }}>Slovaquie</option>
                    <option value="Slovénie" {{ old('country_residence') == 'Slovénie' ? 'selected' : '' }}>Slovénie</option>
                    <option value="Suède" {{ old('country_residence') == 'Suède' ? 'selected' : '' }}>Suède</option>
                    <option value="Suisse" {{ old('country_residence') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                    <option value="Ukraine" {{ old('country_residence') == 'Ukraine' ? 'selected' : '' }}>Ukraine</option>
                    <option value="Vatican" {{ old('country_residence') == 'Vatican' ? 'selected' : '' }}>Vatican</option>

                    <!-- Océanie -->
                    <option value="Australie" {{ old('country_residence') == 'Australie' ? 'selected' : '' }}>Australie</option>
                    <option value="Fidji" {{ old('country_residence') == 'Fidji' ? 'selected' : '' }}>Fidji</option>
                    <option value="Îles Marshall" {{ old('country_residence') == 'Îles Marshall' ? 'selected' : '' }}>Îles Marshall</option>
                    <option value="Îles Salomon" {{ old('country_residence') == 'Îles Salomon' ? 'selected' : '' }}>Îles Salomon</option>
                    <option value="Kiribati" {{ old('country_residence') == 'Kiribati' ? 'selected' : '' }}>Kiribati</option>
                    <option value="Micronésie" {{ old('country_residence') == 'Micronésie' ? 'selected' : '' }}>Micronésie</option>
                    <option value="Nauru" {{ old('country_residence') == 'Nauru' ? 'selected' : '' }}>Nauru</option>
                    <option value="Nouvelle-Zélande" {{ old('country_residence') == 'Nouvelle-Zélande' ? 'selected' : '' }}>Nouvelle-Zélande</option>
                    <option value="Palaos" {{ old('country_residence') == 'Palaos' ? 'selected' : '' }}>Palaos</option>
                    <option value="Papouasie-Nouvelle-Guinée" {{ old('country_residence') == 'Papouasie-Nouvelle-Guinée' ? 'selected' : '' }}>Papouasie-Nouvelle-Guinée</option>
                    <option value="Samoa" {{ old('country_residence') == 'Samoa' ? 'selected' : '' }}>Samoa</option>
                    <option value="Tonga" {{ old('country_residence') == 'Tonga' ? 'selected' : '' }}>Tonga</option>
                    <option value="Tuvalu" {{ old('country_residence') == 'Tuvalu' ? 'selected' : '' }}>Tuvalu</option>
                    <option value="Vanuatu" {{ old('country_residence') == 'Vanuatu' ? 'selected' : '' }}>Vanuatu</option>

                    <!-- Caraïbes -->
                    <option value="Antigua-et-Barbuda" {{ old('country_residence') == 'Antigua-et-Barbuda' ? 'selected' : '' }}>Antigua-et-Barbuda</option>
                    <option value="Bahamas" {{ old('country_residence') == 'Bahamas' ? 'selected' : '' }}>Bahamas</option>
                    <option value="Barbade" {{ old('country_residence') == 'Barbade' ? 'selected' : '' }}>Barbade</option>
                    <option value="Cuba" {{ old('country_residence') == 'Cuba' ? 'selected' : '' }}>Cuba</option>
                    <option value="Dominique" {{ old('country_residence') == 'Dominique' ? 'selected' : '' }}>Dominique</option>
                    <option value="Grenade" {{ old('country_residence') == 'Grenade' ? 'selected' : '' }}>Grenade</option>
                    <option value="Haïti" {{ old('country_residence') == 'Haïti' ? 'selected' : '' }}>Haïti</option>
                    <option value="Jamaïque" {{ old('country_residence') == 'Jamaïque' ? 'selected' : '' }}>Jamaïque</option>
                    <option value="République dominicaine" {{ old('country_residence') == 'République dominicaine' ? 'selected' : '' }}>République dominicaine</option>
                    <option value="Saint-Christophe-et-Niévès" {{ old('country_residence') == 'Saint-Christophe-et-Niévès' ? 'selected' : '' }}>Saint-Christophe-et-Niévès</option>
                    <option value="Sainte-Lucie" {{ old('country_residence') == 'Sainte-Lucie' ? 'selected' : '' }}>Sainte-Lucie</option>
                    <option value="Saint-Vincent-et-les-Grenadines" {{ old('country_residence') == 'Saint-Vincent-et-les-Grenadines' ? 'selected' : '' }}>Saint-Vincent-et-les-Grenadines</option>
                    <option value="Trinité-et-Tobago" {{ old('country_residence') == 'Trinité-et-Tobago' ? 'selected' : '' }}>Trinité-et-Tobago</option>
                </select>
            </div>

            <!-- Mot de passe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        minlength="8">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>
            </div>

            <!-- Conditions -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms" name="terms" type="checkbox" required
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="text-gray-700">
                        J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800 underline">conditions d'utilisation</a> 
                        et la <a href="#" class="text-blue-600 hover:text-blue-800 underline">politique de confidentialité</a> *
                    </label>
                </div>
            </div>

            <!-- Bouton d'inscription -->
            <div>
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                    Créer mon compte Sekaijin
                </button>
            </div>

            <!-- Lien vers connexion -->
            <div class="text-center">
                <p class="text-gray-600">
                    Déjà membre ? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">
                        Se connecter
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    form.addEventListener('submit', function(e) {
        if (password.value !== passwordConfirm.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas');
            passwordConfirm.focus();
        }
    });
});
</script>
@endsection