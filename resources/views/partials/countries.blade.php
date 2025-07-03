@php
$countries = [
    'Afrique du Sud', 'Algérie', 'Angola', 'Bénin', 'Botswana', 'Burkina Faso', 'Burundi', 'Cameroun', 'Cap-Vert', 'Centrafrique', 'Comores', 'Congo', 'Congo (RDC)', 'Côte d\'Ivoire', 'Djibouti', 'Égypte', 'Érythrée', 'Eswatini', 'Éthiopie', 'Gabon', 'Gambie', 'Ghana', 'Guinée', 'Guinée-Bissau', 'Guinée équatoriale', 'Kenya', 'Lesotho', 'Libéria', 'Libye', 'Madagascar', 'Malawi', 'Mali', 'Maroc', 'Maurice', 'Mauritanie', 'Mozambique', 'Namibie', 'Niger', 'Nigéria', 'Ouganda', 'Rwanda', 'Sao Tomé-et-Principe', 'Sénégal', 'Seychelles', 'Sierra Leone', 'Somalie', 'Soudan', 'Soudan du Sud', 'Tanzanie', 'Tchad', 'Togo', 'Tunisie', 'Zambie', 'Zimbabwe',
    'Canada', 'États-Unis', 'Mexique',
    'Belize', 'Costa Rica', 'Guatemala', 'Honduras', 'Nicaragua', 'Panama', 'Salvador',
    'Argentine', 'Bolivie', 'Brésil', 'Chili', 'Colombie', 'Équateur', 'Guyana', 'Paraguay', 'Pérou', 'Suriname', 'Uruguay', 'Venezuela',
    'Afghanistan', 'Arabie saoudite', 'Arménie', 'Azerbaïdjan', 'Bahreïn', 'Bangladesh', 'Bhoutan', 'Birmanie (Myanmar)', 'Brunei', 'Cambodge', 'Chine', 'Chypre', 'Corée du Nord', 'Corée du Sud', 'Émirats arabes unis', 'Géorgie', 'Inde', 'Indonésie', 'Irak', 'Iran', 'Israël', 'Japon', 'Jordanie', 'Kazakhstan', 'Kirghizistan', 'Koweït', 'Laos', 'Liban', 'Malaisie', 'Maldives', 'Mongolie', 'Népal', 'Oman', 'Ouzbékistan', 'Pakistan', 'Palestine', 'Philippines', 'Qatar', 'Singapour', 'Sri Lanka', 'Syrie', 'Tadjikistan', 'Taïwan', 'Thaïlande', 'Timor oriental', 'Turkménistan', 'Turquie', 'Viêt Nam', 'Yémen',
    'Albanie', 'Allemagne', 'Andorre', 'Autriche', 'Belgique', 'Biélorussie', 'Bosnie-Herzégovine', 'Bulgarie', 'Croatie', 'Danemark', 'Espagne', 'Estonie', 'Finlande', 'France', 'Grèce', 'Hongrie', 'Irlande', 'Islande', 'Italie', 'Kosovo', 'Lettonie', 'Liechtenstein', 'Lituanie', 'Luxembourg', 'Macédoine du Nord', 'Malte', 'Moldavie', 'Monaco', 'Monténégro', 'Norvège', 'Pays-Bas', 'Pologne', 'Portugal', 'République tchèque', 'Roumanie', 'Royaume-Uni', 'Russie', 'Saint-Marin', 'Serbie', 'Slovaquie', 'Slovénie', 'Suède', 'Suisse', 'Ukraine', 'Vatican',
    'Australie', 'Fidji', 'Îles Marshall', 'Îles Salomon', 'Kiribati', 'Micronésie', 'Nauru', 'Nouvelle-Zélande', 'Palaos', 'Papouasie-Nouvelle-Guinée', 'Samoa', 'Tonga', 'Tuvalu', 'Vanuatu',
    'Antigua-et-Barbuda', 'Bahamas', 'Barbade', 'Cuba', 'Dominique', 'Grenade', 'Haïti', 'Jamaïque', 'République dominicaine', 'Saint-Christophe-et-Niévès', 'Sainte-Lucie', 'Saint-Vincent-et-les-Grenadines', 'Trinité-et-Tobago'
];
@endphp

@foreach($countries as $country)
    @if (!isset($exclude) || $exclude !== $country)
        <option value="{{ $country }}" {{ $selected == $country ? 'selected' : '' }}>{{ $country }}</option>
    @endif
@endforeach