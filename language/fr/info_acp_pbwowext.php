<?php
/**
 *
 * PBWoW 3.2. An extension for the phpBB Forum Software package.
 * French translation by Galixte (http://www.galixte.com)
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
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
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	// Extension modules
	'ACP_PBWOWEXT_CATEGORY'		=> 'PBWoW 3',
	'ACP_PBWOWEXT_CONFIG'			=> 'Configuration',

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
	'PBWOW_OVERVIEW_TITLE_EXPLAIN'		=> 'Merci d’utiliser PBWoW, nous espérons que cela sera grandement apprécié.',
	'ACP_PBWOW_INDEX_SETTINGS'			=> 'Informations générales',

	'PBWOW_DB_CHECK'					=> 'Vérification de la base de données de PBWoW',
	'PBWOW_DB_GOOD'						=> 'Table de configuration de PBWoW trouvée (%s)',
	'PBWOW_DB_BAD'						=> 'Aucune table de configuration de PBWoW n’a été trouvée. S’assurer que la table (%s) existe dans la base de données du forum phpBB.',
	'PBWOW_DB_BAD_EXPLAIN'				=> 'Permet de désactiver et réactiver l’extension PBWoW 3. Si cela ne fonctionne pas, désactiver l’extension et supprimer ses données. Ensuite, essayer de l’activer à nouveau.',

	'PBWOW_VERSION_CHECK'				=> 'Vérification de la version de PBWoW',
	'PBWOW_LATEST_VERSION'				=> 'Dernière version',
	'PBWOW_EXT_VERSION'					=> 'Version de l’extension',
	'PBWOW_STYLE_VERSION'				=> 'Version du style',
	'PBWOW_LATEST_STYLE_VERSION'		=> 'Dernière version du Style',
	'PBWOW_VERSION_ERROR'				=> 'Impossible de déterminer la version !',
	'PBWOW_CHECK_UPDATE'				=> 'Vérifie <a href="https://www.avathar.be/bbdkp/index.php">avathar.be</a> s’il y a une mise à jour.',

	'PBWOW_DONATE_URL'                  => 'https://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'                  => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                        => 'Donation PayPal',
	'PBWOW_DONATE'						=> 'Faire un don à PBWoW',
	'PBWOW_DONATE_SHORT'				=> 'Faire un don à PBWoW',
	'PBWOW_DONATE_EXPLAIN'				=> 'PBWoW est 100% libre. ce projet est un passe-temps où je consacre mon temps et mon argent, juste pour le plaisir. Si l’extension PBWoW est appréciée, il est possible de réaliser un don. Il sera grandement apprécié. Sans contrepartie.',

	// CONFIG //

	'PBWOW_CONFIG_TITLE'				=> 'Configuration de PBWoW',
	'PBWOW_CONFIG_TITLE_EXPLAIN'		=> 'Depuis cette page il est possible de configurer les options de personnalisation du style PBWoW.',

	'PBWOW_LOGO'						=> 'Logo personnalisé',
	'PBWOW_LOGO_ENABLE'					=> 'Activer son logo personnalisé',
	'PBWOW_LOGO_ENABLE_EXPLAIN'			=> 'Permet d’activer son propre logo personnalisé pour tous les styles PBWoW installés (sauf le style maître de PBWoW).',
	'PBWOW_LOGO_SRC'					=> 'Chemin de l’image source',
	'PBWOW_LOGO_SRC_EXPLAIN'			=> 'Permet de saisir le chemin de l’image depuis le répertoire racine de son forum phpBB, exemple <samp>images/logo.png</samp>.<br />Il est conseillé fortement d’utiliser une image PNG avec un fond transparent.',
	'PBWOW_LOGO_SIZE'					=> 'Dimensions du logo',
	'PBWOW_LOGO_SIZE_EXPLAIN'			=> 'Permet de saisir les dimensions exactes de l’image de son logo (Largeur x Hauteur en pixels).<br />Les images aux dimensions supérieures à 350 x 200 ne sont pas conseillées (en raison de la mise en page réactive).',
	'PBWOW_LOGO_MARGINS'				=> 'Marges du logo',
	'PBWOW_LOGO_MARGINS_EXPLAIN'		=> 'Permet de définir les marges CSS de son logo. Cela permettra de contrôler davantage le positionnement de son image. Utiliser un balisage CSS valide, exemple <samp>10px 5px 25px 0</samp>.',

	'PBWOW_AVATARS'						=> 'Avatars de jeu',
	'PBWOW_AVATARS_ENABLE'				=> 'Activer le support étendu des avatars de jeu (et des icônes)',
	'PBWOW_AVATARS_ENABLE_EXPLAIN'		=> 'Si activé, votre forum affichera un avatar de jeu généré en fonction de bbGuild si l’utilisateur n’a pas configuré d’avatar personnalisé.',
	'PBWOW_SMALLRANKS_ENABLE'			=> 'Utiliser de petites images de rang',
	'PBWOW_SMALLRANKS_ENABLE_EXPLAIN'	=> 'Activer cette option pour utiliser de petites images de rang qui se superposent à l’avatar. Ne pas activer cette option si vous utilisez de plus grandes images de rang.',

	'PBWOW_TOPBAR'						=> 'Barre située tout en haut de l’entête',
	'PBWOW_TOPBAR_ENABLE'				=> 'Activer la barre située au-dessus de l’entête',
	'PBWOW_TOPBAR_ENABLE_EXPLAIN'		=> 'Permet d’activer la barre au-dessus de l’entête du forum. D’une hauteur de 40px elle sera affichée tout en haut de chaque page du forum.',
	'PBWOW_TOPBAR_CODE'					=> 'Code de la barre située au-dessus de l’entête',
	'PBWOW_TOPBAR_CODE_EXPLAIN'			=> 'Permet de saisir le code de la barre. Utiliser les éléments &lt;span&gt; ou &lt;a class="cell"&gt; pour marquer les limites entre les blocs. Pour utiliser des icônes, utiliser les balises &lt;img&gt; ou définir des classes CSS dans la feuille de style custom.css (meilleure solution).',
	'PBWOW_TOPBAR_FIXED'				=> 'Fixer en haut',
	'PBWOW_TOPBAR_FIXED_EXPLAIN'		=> 'Permet de fixer la barre située tout en haut de l’entête afin qu’elle soit toujours visible et verrouillée, même lorsque la page défile.<br />Cela ne s’applique pas aux appareils mobiles. La barre reviendra à son mode par défaut (défilement) lorsqu’elle sera vue sur de petits écrans.',

	'PBWOW_HEADERLINKS'					=> 'Liens personnalisés de la boite de l’entête',
	'PBWOW_HEADERLINKS_ENABLE'			=> 'Activer les liens personnalisés de la boite de l’entête',
	'PBWOW_HEADERLINKS_ENABLE_EXPLAIN'	=> 'Permet d’activer les liens personnalisés dans la boite de l’entête du forum. En activant cette option, le code HTML saisi ci-dessous s’affichera dans la boite située en haut à droite de l’écran (dans la ligne avant le lien FAQ). C’est utile pour les liens du portail et les liens DKP (dont certains seront détectés automatiquement).',
	'PBWOW_HEADERLINKS_CODE'			=> 'Code des liens personnalisés de la boite de l’entête',
	'PBWOW_HEADERLINKS_CODE_EXPLAIN'	=> 'Permet de saisir les liens personnalisés ici. Ceux-ci devraient être encadrés par les éléments &lt;li&gt;. Pour utiliser des icônes, définir des classes CSS dans la feuille de style custom.css.',

	'PBWOW_VIDEOBG'						=> 'Paramètre de l’arrière plan (Vidéo)',
	'PBWOW_VIDEOBG_ENABLE'				=> 'Activer les arrières plans vidéo animés',
	'PBWOW_VIDEOBG_ENABLE_EXPLAIN'		=> 'Permet d’activer les arrière-plans vidéo animés. Certains styles de PBWoW supportent des arrières plans vidéo animés (pas tous). Il est possible d’activer ces derniers pour un rendu original, ou les désactiver pour économiser de la bande passante (ou si des problèmes sont constatés).',
	'PBWOW_VIDEOBG_ALLPAGES'			=> 'Afficher les arrières plans vidéo sur toutes les pages',
	'PBWOW_VIDEOBG_ALLPAGES_EXPLAIN'	=> 'Permet d’afficher les arrière-plans vidéo sur toutes les pages du forum. Par défaut, PBWoW charge seulement les arrières plans vidéo (si disponibles) sur la page <u>index.php</u>. Il est possible d’étendre l’affichage des arrières plans vidéo sur toutes les pages, mais cela peut affecter la vitesse de navigation de des visiteurs (pas la bande passante de du serveur, car ils sont mis en cache localement). [ne s’applique que si la vidéo est activée]',
	'PBWOW_FIXEDBG'						=> 'Fixer la position de l’arrière plan',
	'PBWOW_FIXEDBG_EXPLAIN'				=> 'Permet de fixer la position de l’arrière plan permet d’éviter le défilement avec le reste du contenu. Garder à l’esprit que certains appareils ayant une faible résolution n’auront pas la possibilité de voir l’image d’arrière plan toute entière.',

	'PBWOW_ADS_INDEX'					=> 'Encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_ENABLE'			=> 'Activer l’encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_ENABLE_EXPLAIN'	=> 'Permet d’afficher un encart publicitaire sur la page de l’index du forum (requiert l’extension Recent Topics).',
	'PBWOW_ADS_INDEX_CODE'				=> 'Code de l’encart publicitaire sur l’index',
	'PBWOW_ADS_INDEX_CODE_EXPLAIN'		=> 'Permet de saisir le code de l’encart publicitaire sur la page de l’index du forum. Cet encart est adapté aux publicités ayant une <u>largeur</u> de : <b>300px</b>.<br />Pour utiliser/modifier un feuille style CSS personnalisée, merci d’ajouter ceci dans le fichier <samp>styles/pbwow3/theme/custom.css</samp>.',
));
