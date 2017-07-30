PBWoW 3 Extension for phpBB 3.1
=========

Extension for phpBB 3.1 to enhance PBWoW 3.2 style with additional functionality.

## Version 
3.0.5b

## Features
- Game avatars and profile icons (WoW, Diablo 3 & Wildstar) generated based on custom profile fields
- Automatically configured gaming profile fields
- Battle.net API functions to get WoW character information (incl. avatars) from live servers
- ACP module features:
  - Version update checking
  - Compatibility and config check, to see if PBWoW 3 has been configured correctly
  - Gaming avatars settings
  - Quick access to enable/disable profile fields for specific games
  - Custom logo settings (enable/disable, url, size, margins)
  - Custom header-bar (enable/disable, content, fixed to top)
  - Custom header-box links (enable/disable, content)
  - Video background settings (enable/disable, display on all pages or index only, fixed position)

#### Requirements
- phpBB 3.1.3 or higher
- PHP 5.3.3 or higher

#### Languages supported
- English

## Installation
1. [Download the latest release](https://github.com/bbdkp/PBWoW3ext/releases) and unzip it.
2. Copy the entire contents from the unzipped folder to `phpBB/ext/paybas/pbwow/`.
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Find `PBWoW 3 Extension` under "Disabled Extensions" and click `Enable`.

## Uninstallation
1. Navigate in the ACP to `Customise -> Manage extensions`.
2. Click the `Disable` link for `PBWoW 3 Extension`.
3. To permanently uninstall, click `Delete Data`, then delete the `pbwow` folder from `phpBB/ext/paybas/`.

### License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2015 - PayBas
