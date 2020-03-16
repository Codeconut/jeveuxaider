<?php

return [

    /*
    |--------------------------------------------------------------------------
    | YOUNG WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'young_workflow_states' => [
        "vocabulary" => "Statut",
        "terms" => [
            "En attente de mission" => "En attente de mission",
            "Mission proposée" => "Mission proposée",
            "Mission refusée" => "Mission refusée",
            "Mission en cours" => "Mission en cours",
            "Abandon de mission" => "Abandon de mission",
            "Exclusion de la mission" => "Exclusion de la mission",
            "Arrêt de la mission" => "Arrêt de la mission",
            "Mission effectuée" => "Mission effectuée"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST RATINGS
    |--------------------------------------------------------------------------
    |
    */
    'interest_ratings' => [
        "vocabulary" => "Intérets",
        "terms" => [
            "Très intéressé" => "Très intéressé",
            "Assez intéressé" => "Assez intéressé",
            "Intéressé" => "Intéressé",
            "Peu intéressé" => "Peu intéressé",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE TYPES
    |--------------------------------------------------------------------------
    |
    */
    'interet_defense_types' => [
        "vocabulary" => "Types",
        "terms" => [
            "Au sein d'une préparation militaire ?" => "Au sein d'une préparation militaire ?",
            "Au sein d'une association de souvenir" => "Au sein d'une association de souvenir",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    'interet_defense_domaines' => [
        "vocabulary" => "Domaines",
        "terms" => [
            "Armée de terre" => "Armée de terre",
            "Armée de l'air" => "Armée de l'air",
            "Marine" => "Marine",
            "Pas de préférence" => "Pas de préférence"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    'interet_securite_domaines' => [
        "vocabulary" => "Domaines",
        "terms" => [
            "En tant que pompier volontaire" => "En tant que pompier volontaire",
            "Au sein de la gendarmerie" => "Au sein de la gendarmerie",
            "Dans une association de protection civile" => "Dans une association de protection civile",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | YOUNG SITUATIONS
    |--------------------------------------------------------------------------
    |
    */
    'young_situations' => [
        "vocabulary" => "Situations",
        "terms" => [
            "Scolarité - externe" => "Scolarité - externe",
            "Scolarisé - demi-pensionnaire" => "Scolarisé - demi-pensionnaire",
            "Scolarisé - interne" => "Scolarisé - interne",
            "Salarié" => "Salarié",
            "Apprenti" => "Apprenti",
            "Autre" => "Autre",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'structure_workflow_states' => [
        "vocabulary" => "Statut",
        "terms" => [
            "En attente de validation" => "En attente de validation",
            "En attente de correction" => "En attente de correction",
            "Validée" => "Validée",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'mission_workflow_states' => [
        "vocabulary" => "Statut",
        "terms" => [
            "Brouillon" => "Brouillon",
            "En attente de validation" => "En attente de validation",
            "En attente de correction" => "En attente de correction",
            "Validée" => "Validée",
            "Refusée" => "Refusée",
            "Annulée" => "Annulée",
            "Archivée" => "Archivée"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION OPEN HANDICAP
    |--------------------------------------------------------------------------
    |
    */
    'mission_handicap' => [
        "vocabulary" => "Mission ouverte aux personnes en situation de handicap",
        "terms" => [
            "Oui" => "Oui",
            "Oui mais en adaptant la mission" => "Oui mais en adaptant la mission",
            "Non" => "Non"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE LEGAL STATUS
    |--------------------------------------------------------------------------
    |
    */
    'structure_legal_status' => [
        "vocabulary" => "Statut Juridique",
        "terms" => [
            "Association" => "Association",
            "Structure publique" => "Structure publique",
            "Structure privée" => "Structure privée",
            "Autre" => "Autre",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | ASSOCIATIONS TYPES
    |--------------------------------------------------------------------------
    |
    */
    'association_types' => [
        "vocabulary" => "Types d'association",
        "terms" => [
            "Agrément jeunesse et éducation populaire" => "Agrément jeunesse et éducation populaire",
            "Agrément service civique" => "Agrément service civique",
            "Association complémentaire de l'enseignement public" => "Association complémentaire de l'enseignement public",
            "Associations d'usagers du système de santé" => "Associations d'usagers du système de santé",
            "Association sportive affiliée à une fédération sportive agréée par l'État" => "Association sportive affiliée à une fédération sportive agréée par l'État",
            "Agrément des associations de protection de l'environnement" => "Agrément des associations de protection de l'environnement",
            "Association agréée de sécurité civile" => "Association agréée de sécurité civile",
            "Autre agrément" => "Autre agrément",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PUBLIQUE TYPES
    |--------------------------------------------------------------------------
    |
    */
    'structure_publique_types' => [
        "vocabulary" => "Types structure publique",
        "terms" => [
            "Collectivité territoriale" => "Collectivité territoriale",
            "Etablissement scolaire" => "Etablissement scolaire",
            "Etablissement public de santé" => "Etablissement public de santé",
            "Etablissement public" => "Etablissement public",
            "Service de l'Etat" => "Service de l'Etat",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | STRUCTURE PUBLIQUE ETAT TYPES
    |--------------------------------------------------------------------------
    |
    */
    'structure_publique_etat_types' => [
        "vocabulary" => "Types établissement publique",
        "terms" => [
            "SDIS (Service départemental d'Incendie et de Secours)" => "SDIS (Service départemental d'Incendie et de Secours)",
            "Gendarmerie" => "Gendarmerie",
            "Police" => "Police",
            "Armées" => "Armées",
            "Autre service de l'état" => "Autre service de l'état",
            "Autre établissement public" => "Autre établissement public",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PRIVEE TYPES
    |--------------------------------------------------------------------------
    |
    */
    'structure_privee_types' => [
        "vocabulary" => "Types structure publique",
        "terms" => [
            "Établissement de santé privé d'intérêt collectif" => "Établissement de santé privé d'intérêt collectif",
            "Entreprise agréée ESUS" => "Entreprise agréée ESUS",
            "Autre" => "Autre",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION TYPES
    |--------------------------------------------------------------------------
    |
    */
    'mission_formats' => [
        "vocabulary" => "Formats de mission",
        "terms" => [
            "Perlée" => "Mission perlée (84 heures tout au long de l'année)",
            "Continue" => "Mission continue (12 jours d'affilée sauf exception)",
            "Autonome" => "Projet autonome",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION PERIODES
    |--------------------------------------------------------------------------
    |
    */
    'mission_periodes' => [
        "vocabulary" => "Périodes",
        "terms" => [
            'Pendant les vacances scolaires' => "Pendant les vacances scolaires",
            'En-dehors des vacances scolaires (mercredi après-midi, soirées et/ou weekends)' => "En-dehors des vacances scolaires (mercredi après-midi, soirées et/ou weekends)",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    'mission_domaines' => [
        "vocabulary" => "Domaines de mission",
        "terms" => [
            "Défense et mémoire" => "Défense et mémoire",
            "Sécurité" => "Sécurité",
            "Solidarité" => "Solidarité",
            "Santé" => "Santé",
            "Éducation" => "Éducation",
            "Culture" => "Culture",
            "Sport" => "Sport",
            "Environnement et développement durable" => "Environnement et développement durable",
            "Citoyenneté" => "Citoyenneté",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | Départements
    |--------------------------------------------------------------------------
    |
    */
    'departments' => [
        "vocabulary" => "Départements",
        "terms" => [
            "01" => "Ain",
            "02" => "Aisne",
            "03" => "Allier",
            "04" => "Alpes-de-Haute-Provence",
            "05" => "Hautes-Alpes",
            "06" => "Alpes-Maritimes",
            "07" => "Ardèche",
            "08" => "Ardennes",
            "09" => "Ariège",
            "10" => "Aube",
            "11" => "Aude",
            "12" => "Aveyron",
            "13" => "Bouches-du-Rhône",
            "14" => "Calvados",
            "15" => "Cantal",
            "16" => "Charente",
            "17" => "Charente-Maritime",
            "18" => "Cher",
            "19" => "Corrèze",
            "21" => "Côte-d'Or",
            "22" => "Côtes-d'Armor",
            "23" => "Creuse",
            "24" => "Dordogne",
            "25" => "Doubs",
            "26" => "Drôme",
            "27" => "Eure",
            "28" => "Eure-et-Loire",
            "29" => "Finistère",
            "2A" => "Corse-du-Sud",
            "2B" => "Haute-Corse",
            "30" => "Gard",
            "31" => "Haute-Garonne",
            "32" => "Gers",
            "33" => "Gironde",
            "34" => "Hérault",
            "35" => "Ille-et-Vilaine",
            "36" => "Indre",
            "37" => "Indre-et-Loire",
            "38" => "Isère",
            "39" => "Jura",
            "40" => "Landes",
            "41" => "Loir-et-Cher",
            "42" => "Loire",
            "43" => "Haute-Loire",
            "44" => "Loire-Atlantique",
            "45" => "Loiret",
            "46" => "Lot",
            "47" => "Lot-et-Garonne",
            "48" => "Lozère",
            "49" => "Maine-et-Loire",
            "50" => "Manche",
            "51" => "Marne",
            "52" => "Haute-Marne",
            "53" => "Mayenne",
            "54" => "Meurthe-et-Moselle",
            "55" => "Meuse",
            "56" => "Morbihan",
            "57" => "Moselle",
            "58" => "Nièvre",
            "59" => "Nord",
            "60" => "Oise",
            "61" => "Orne",
            "62" => "Pas-de-Calais",
            "63" => "Puy-de-Dôme",
            "64" => "Pyrénées-Atlantiques",
            "65" => "Hautes-Pyrénées",
            "66" => "Pyrénées-Orientales",
            "67" => "Bas-Rhin",
            "68" => "Haut-Rhin",
            "69" => "Rhône",
            "70" => "Haute-Saône",
            "71" => "Saône-et-Loire",
            "72" => "Sarthe",
            "73" => "Savoie",
            "74" => "Haute-Savoie",
            "75" => "Paris",
            "76" => "Seine-Maritime",
            "77" => "Seine-et-Marne",
            "78" => "Yvelines",
            "79" => "Deux-Sèvres",
            "80" => "Somme",
            "81" => "Tarn",
            "82" => "Tarn-et-Garonne",
            "83" => "Var",
            "84" => "Vaucluse",
            "85" => "Vendée",
            "86" => "Vienne",
            "87" => "Haute-Vienne",
            "88" => "Vosges",
            "89" => "Yonne",
            "90" => "Territoire de Belfort",
            "91" => "Essonne",
            "92" => "Hauts-de-Seine",
            "93" => "Seine-Saint-Denis",
            "94" => "Val-de-Marne",
            "95" => "Val-d'Oise",
            "971" => "Guadeloupe",
            "972" => "Martinique",
            "973" => "Guyane",
            "974" => "La Réunion",
            "976" => "Mayotte",
            "987" => "Polynésie française"
        ]
    ],
];
