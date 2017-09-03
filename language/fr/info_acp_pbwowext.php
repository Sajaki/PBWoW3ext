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
	'ACP_PBWOWEXT_CATEGORY'		=> 'PBWoW 3',
	'ACP_PBWOWEXT_CONFIG'		=> 'Configuration',

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
	'PBWOW_LATEST_STYLE_VERSION'		=> 'Dernière version du Style',
	'PBWOW_VERSION_ERROR'				=> 'Impossible de déterminer la version !',
	'PBWOW_CHECK_UPDATE'				=> 'Vérifie <a href="http://www.avathar.be/bbdkp/index.php">avathar.be</a> s’il y a une mise à jour.',

	'PBWOW_CHECK_URL'                   => 'http://www.avathar.be/versioncheck/pbwowext.json',
	'PBWOW_DONATE_URL'                  => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'                  => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                        => 'Donation PayPal',
	'PBWOW_DONATE'						=> 'Faire un don à PBWoW',
	'PBWOW_DONATE_SHORT'				=> 'Faire un don à PBWoW',
	'PBWOW_DONATE_EXPLAIN'				=> 'PBWoW est 100% libre. ce projet est un passe-temps où je consacre mon temps et mon argent, juste pour le plaisir. Si vous appréciez PBWoW, vous pouvez envisager de faire un don. Il sera grandement apprécié. Sans contrepartie.',

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

	'PBWOW_ADS_INDEX'					=> 'Encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_ENABLE'			=> 'Activer l’encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_ENABLE_EXPLAIN'	=> 'Ceci affichera un encart publicitaire sur la page de l’index du forum (requiert l’extension Recent Topics).',
	'PBWOW_ADS_INDEX_CODE'				=> 'Code de l’encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_CODE_EXPLAIN'		=> 'Cet encart est adapté aux publicités ayant une <u>largeur</u> de : <b>300px</b>.<br />Si vous souhaitez utiliser/modifier un feuille style CSS personnalisée, veuillez ajouter ceci dans le fichier <samp>styles/pbwow3/theme/custom.css</samp>.',
));
