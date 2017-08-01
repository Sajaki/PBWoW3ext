<?php
/**
 *
 * @package PBWoW Extension
 * French translation by Galixte (http://www.galixte.com)
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	// Extension modules
	'ACP_PBWOW3_CATEGORY'		=> 'PBWoW 3',
	'ACP_PBWOW3_OVERVIEW'		=> 'Vue d’ensemble',
	'ACP_PBWOW3_CONFIG'			=> 'Configuration',

	// Common
	'PBWOW_ACTIVE'				=> 'actif',
	'PBWOW_INACTIVE'			=> 'inactif',
	'PBWOW_DETECTED'			=> 'détecté',
	'PBWOW_NOT_DETECTED'		=> 'non détecté',
	'PBWOW_OBSOLETE'			=> 'n’est plus utilisé',
	'PBWOW_FLUSH'				=> 'Vider',
	'PBWOW_FATAL'				=> 'Erreur fatale ! Cela ne devrait jamais arriver.',

	'LOG_PBWOW_CONFIG'			=> '<strong>Paramètres de PBWoW modifiés</strong><br />&raquo; %s',


	// OVERVIEW //

	'PBWOW_OVERVIEW_TITLE'				=> 'Vue d’ensemble de l’extension PBWoW',
	'PBWOW_OVERVIEW_TITLE_EXPLAIN'		=> 'Merci d’utiliser PBWoW, nous espérons que cela vous plaira.',
	'ACP_PBWOW_INDEX_SETTINGS'			=> 'Informations générales',

	'PBWOW_DB_CHECK'					=> 'Vérification de la base de données de PBWoW',
	'PBWOW_DB_GOOD'						=> 'Table de configuration de PBWoW trouvée (%s)',
	'PBWOW_DB_BAD'						=> 'Aucune table de configuration de PBWoW n’a été trouvée. S’assurer que la table (%s) existe dans la base de données du forum phpBB.',
	'PBWOW_DB_BAD_EXPLAIN'				=> 'Essayer de désactiver et réactiver l’extension PBWoW 3. Si cela ne fonctionne pas, désactiver l’extension et supprimer ses données. Ensuite, essayer de l’activer à nouveau.',

	'PBWOW_VERSION_CHECK'				=> 'Vérification de la version de PBWoW',
	'PBWOW_LATEST_VERSION'				=> 'Dernière version',
	'PBWOW_EXT_VERSION'					=> 'Version de l’extension',
	'PBWOW_STYLE_VERSION'				=> 'Version du style',
	'PBWOW_VERSION_ERROR'				=> 'Impossible de déterminer la version !',
	'PBWOW_CHECK_UPDATE'				=> 'Vérifie <a href="http://www.avathar.be/bbdkp/index.php">avathar.be</a> s’il y a une mise à jour.',

	'PBWOW_CPF_CHECK'					=> 'Vérification des champs de profil personnalisés',
	//'PBWOW_CPF_CREATE_LOCATION'			=> 'Créer ou activer ces champs depuis le panneau d’administration > Utilisateurs et Groupes > Champs de profil personnalisés',
	'PBWOW_CPF_LOAD_LOCATION'			=> 'Activer ces champs depuis le panneau d’administration > Général > Configuration Générale > Fonctionnalités du forums',
	'PBWOW_GAME_EXPLAIN'				=> 'Les champs de profil personnalisés pour ce jeu sont actuellement désactivés.',

	'PBWOW_BNETCHARS_CHECK'				=> 'Fonctionnalités sur les informations des personnages avec l’API de Battle.net',
	'PBWOW_CHARSDB_GOOD'				=> 'Table des personnages de PBWoW trouvée (%s)',
	'PBWOW_CHARSDB_BAD'					=> 'Aucune table des personnages de PBWoW n’a été trouvée. S’assurer que la table (%s) existe dans la base de données du forum phpBB.',
	'PBWOW_CHARSDB_BAD_EXPLAIN'			=> 'La table des personnages Battle.net de PBWoW 3 pour devrait avoir été installée automatiquement lorsque vous avez activé l’extension PBWoW. Veuillez désactiver l’extension, supprimer ses données et essayer de l’activer à nouveau.',
	'PBWOW_CHARSDB_FLUSH'				=> 'Vider la tables des personnages',
	'PBWOW_CHARSDB_FLUSH_EXPLAIN'		=> 'Cela effacera toutes les informations sur les personnages Battle.net stockées dans la base de données. Elles seront à nouveau récupérées automatiquement si nécessaire.',
	'PBWOW_CURL_BAD'					=> 'Votre serveur ne supporte pas &quot;cURL&quot; !',
	'PBWOW_CURL_BAD_EXPLAIN'			=> 'Vérifier la configuration de votre serveur, ou contacter votre hébergeur. Désactiver l’API de Battle.net API jusqu’à ce que cURL soit activé !',

	'PBWOW_DONATE'						=> 'Faire un don à PBWoW',
	'PBWOW_DONATE_SHORT'				=> 'Faire un don à PBWoW',
	'PBWOW_DONATE_EXPLAIN'				=> 'PBWoW est 100% libre. ce projet est un passe-temps où je consacre mon temps et mon argent, juste pour le plaisir. Si vous appréciez PBWoW, vous pouvez envisager de faire un don. Il sera grandement apprécié. Sans contrepartie.',


	// LEGACY CHECKS //

	'PBWOW_LEGACY_CHECK'				=> 'Vérification de l’antériorité de PBWoW',

	'PBWOW_LEGACY_CONSTANTS'			=> 'Vérification des problèmes persistants de PBWoW',
	'PBWOW_LEGACY_CONSTANTS_EXPLAIN'	=> 'Si détecté, cela signifie qu’il y a des MODs (partiels) de PBWoW v1 ou v2 actifs ! Cela pourrait conduire à des erreurs. C’est pourquoi nous vous recommandons fortement de désactiver tout MOD actif (PBWoW) avant de passer à la dernière version de phpBB. Ou d’installer une version propre de phpBB et d’utiliser la fonction de mise à jour de la base de données de l’installateur de phpBB.',
	'PBWOW_LEGACY_DATABASE'				=> 'PBWoW Legacy Database(s)',
	'PBWOW_LEGACY_DATABASE_EXPLAIN'		=> 'La table de configuration de PBWoW v1 ou v2 est toujours active. Ce n’est pas un problème, puisque PBWoW 3 n’interagit pas avec elle. Mais vous pouvez la supprimer la table si vous voulez (elle ne sera plus utilisée).',

	'PBWOW_LEGACY_NONE'					=> 'Aucun problème antérieur concernant des anciennes versions de PBWoW n’a été trouvé. C’est bon signe.',


	// CONFIG //

	'PBWOW_CONFIG_TITLE'				=> 'Configuration de PBWoW',
	'PBWOW_CONFIG_TITLE_EXPLAIN'		=> 'Ici vous pouvez choisir quelques options pour personnaliser votre installation de PBWoW.',

	'PBWOW_LOGO'						=> 'Logo personnalisé',
	'PBWOW_LOGO_ENABLE'					=> 'Activer votre propre logo personnalisé',
	'PBWOW_LOGO_ENABLE_EXPLAIN'			=> 'Active votre propre logo personnalisé pour tous les styles PBWoW installés (sauf le style maître de PBWoW).',
	'PBWOW_LOGO_SRC'					=> 'Chemin de l’image source',
	'PBWOW_LOGO_SRC_EXPLAIN'			=> 'Chemin de l’image depuis le répertoire racine de votre forum phpBB, exemple <samp>images/logo.png</samp>.<br />Je vous conseille fortement d’utiliser une image PNG avec un fond transparent.',
	'PBWOW_LOGO_SIZE'					=> 'Dimensions du logo',
	'PBWOW_LOGO_SIZE_EXPLAIN'			=> 'Dimensions exactes de l’image de votre logo (Largeur x Hauteur en pixels).<br />Les images aux dimensions supérieures à 350 x 200 ne sont pas conseillées (en raison de la mise en page réactive).',
	'PBWOW_LOGO_MARGINS'				=> 'Marges du logo',
	'PBWOW_LOGO_MARGINS_EXPLAIN'		=> 'Définir les marges CSS de votre logo. Cela permettra de contrôler davantage le positionnement de votre image. Utiliser un balisage CSS valide, exemple <samp>10px 5px 25px 0</samp>.',

	'PBWOW_TOPBAR'						=> 'Bar située tout en haut de l’en-tête',
	'PBWOW_TOPBAR_ENABLE'				=> 'Activer la bar située tout en haut de l’en-tête',
	'PBWOW_TOPBAR_ENABLE_EXPLAIN'		=> 'En activant cette option, une barre personnalisable haute de 40px sera affichée tout en haut de chaque page.',
	'PBWOW_TOPBAR_CODE'					=> 'Code de la bar située tout en haut de l’en-tête',
	'PBWOW_TOPBAR_CODE_EXPLAIN'			=> 'Saisir votre code ici. Utiliser les éléments &lt;span&gt; ou &lt;a class="cell"&gt; pour marquer les limites entre les blocs. Pour utiliser des icônes, utiliser les balises &lt;img&gt; ou définir des classes CSS dans votre feuille de style custom.css (meilleure solution).',
	'PBWOW_TOPBAR_FIXED'				=> 'Fixer en haut',
	'PBWOW_TOPBAR_FIXED_EXPLAIN'		=> 'Fixer la bar située tout en haut de l’en-tête afin qu’elle soit toujours visible et verrouillée, même lorsque la page défile.<br />Cela ne s’applique pas aux appareils mobiles. La bar reviendra à son mode par défaut (défilement) lorsqu’elle sera vue sur de petits écrans.',

	'PBWOW_HEADERLINKS'					=> 'Liens personnalisés de la boite de l’en-tête',
	'PBWOW_HEADERLINKS_ENABLE'			=> 'Activer les liens personnalisés de la boite de l’en-tête',
	'PBWOW_HEADERLINKS_ENABLE_EXPLAIN'	=> 'En activant cette option, le code HTML saisi ci-dessous s’affichera dans la boîte située en haut à droite de l’écran (dans la ligne avant le lien FAQ). C’est utile pour les liens du portail et les liens DKP (dont certains seront détectés automatiquement).',
	'PBWOW_HEADERLINKS_CODE'			=> 'Code des liens personnalisés de la boite de l’en-tête',
	'PBWOW_HEADERLINKS_CODE_EXPLAIN'	=> 'Saisir vos liens personnalisés ici. Ceux-ci devraient être encadrés par les éléments &lt;li&gt;. Pour utiliser des icônes, définir des classes CSS dans votre feuille de style custom.css.',

	'PBWOW_VIDEOBG'						=> 'Paramètre de l’arrière plan (Vidéo)',
	'PBWOW_VIDEOBG_ENABLE'				=> 'Activer les arrières plans vidéo animés',
	'PBWOW_VIDEOBG_ENABLE_EXPLAIN'		=> 'Certains styles de PBWoW supportent des arrières plans vidéo animés (pas tous). Vous pouvez activer ces derniers pour un rendu original, ou les désactiver pour économiser votre bande passante (ou si vous rencontrez des problèmes).',
	'PBWOW_VIDEOBG_ALLPAGES'			=> 'Afficher les arrières plans vidéo sur toutes les pages',
	'PBWOW_VIDEOBG_ALLPAGES_EXPLAIN'	=> 'Par défaut, PBWoW charge seulement les arrières plans vidéo (si disponibles) sur la page <u>index.php</u>. Vous pouvez étendre l’affichage des arrières plans vidéo sur toutes les pages, mais cela peut affecter la vitesse de navigation de vos visiteurs (pas la bande passante de votre serveur, car ils sont mis en cache localement). [ne s’applique que si la vidéo est activée]',
	'PBWOW_FIXEDBG'						=> 'Fixer la position de l’arrière plan',
	'PBWOW_FIXEDBG_EXPLAIN'				=> 'Fixer la position de l’arrière plan permet d’éviter le défilement avec le reste du contenu. Gardez à l’esprit que certains appareils ayant une faible résolution n’auront pas la possibilité de voir l’image d’arrière plan toute entière.',

	'PBWOW_AVATARS'						=> 'Avatars de jeu',
	'PBWOW_AVATARS_ENABLE'				=> 'Activer le support étendu des avatars de jeu (et des icônes)',
	'PBWOW_AVATARS_ENABLE_EXPLAIN'		=> 'Si activé, votre forum affichera un avatar de jeu généré (en fonction des champs du profil) si l’utilisateur n’a pas configuré d’avatar personnalisé.',
	'PBWOW_AVATARS_PATH'				=> 'Chemin des avatars de jeu',
	'PBWOW_AVATARS_PATH_EXPLAIN'		=> 'Chemin depuis le répertoire racine de votre forum phpBB où les avatars de jeu seront stockés, exemple <samp>images/avatars/gaming</samp>.<br />Les icônes des personnages exigent également ce chemin pour être utilisés.',
	'PBWOW_SMALLRANKS_ENABLE'			=> 'Utiliser de petites images de rang',
	'PBWOW_SMALLRANKS_ENABLE_EXPLAIN'	=> 'Activer cette option pour utiliser de petites images de rang qui se superposent à l’avatar (comme c’est le cas sur PBWoW.com). Ne pas activer cette option si vous utilisez de plus grandes images de rang.',

	'PBWOW_BNET_APIKEY'					=> 'Clé de l’API Battle.net',
	'PBWOW_BNET_APIKEY_EXPLAIN'			=> 'Saisir votre clé du jeu pour l’API Battle.net. Si vous n’en avez pas, il est possible d’en obtenir une en créant un <a href="https://dev.battle.net/member/register">compte Mashery</a>.',
	'PBWOW_BNETCHARS'					=> 'Informations sur les personnages Battle.net',
	'PBWOW_BNETCHARS_ENABLE'			=> 'Activer l’API Battle.net pour les informations des personnages',
	'PBWOW_BNETCHARS_ENABLE_EXPLAIN'	=> 'Activer cette fonctionnalité pour utilise l’API Battle.net afin de récupérer les informations des personnages (lorsqu’elles sont disponibles), pour les utiliser dans les profils d’utilisateur. Le paramètre des <u>avatars de jeu</u> doit être activé pour afficher les avatars Battle.net !',
	'PBWOW_BNETCHARS_CACHETIME'			=> 'Durée de vie du cache',
	'PBWOW_BNETCHARS_CACHETIME_EXPLAIN'	=> 'Paramétrer la durée de vie du cache (en secondes) des informations des personnages après qu’elles aient été récupérées à partir de l’API Battle.net. Vous pouvez modifier ce paramètre pour mettre à jour les informations des personnages plus ou moins fréquemment. 86400 = 24h',
	'PBWOW_BNETCHARS_TIMEOUT'			=> 'Délai de la requête de l’API',
	'PBWOW_BNETCHARS_TIMEOUT_EXPLAIN'	=> 'Définir le temps écoulé limite (en secondes) pour les requêtes vers l’API Battle.net. Il s’agit du temps maximum que le script attendra avant que Battle.net réponde. Augmenter la valeur si les données (correctes) ne sont pas reçues à temps, mais le temps de chargement de la page peut augmenter !',

	'PBWOW_ADS_INDEX'					=> 'Encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_ENABLE'			=> 'Activer l’encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_ENABLE_EXPLAIN'	=> 'Ceci affichera un encart publicitaire sur la page de l’index du forum (requiert l’extension Recent Topics).',
	'PBWOW_ADS_INDEX_CODE'				=> 'Code de l’encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_CODE_EXPLAIN'		=> 'Cet encart est adapté aux publicités ayant une <u>largeur</u> de : <b>300px</b>.<br />Si vous souhaitez utiliser/modifier un feuille style CSS personnalisée, veuillez ajouter ceci dans le fichier <samp>styles/pbwow3/theme/custom.css</samp>.',
));
