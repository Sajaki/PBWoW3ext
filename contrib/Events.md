## List of events

 Event name :  paybas.pbwow.modify_process_pf_before

 Description : Event to modify the profile field processing script before the supported games are processed

 Placement : pbwow.process_pf_show

 Added in  : pbwow 3

 Arguments :

   - @var    array   tpl_fields        Array with template data fields
   - @var    string  avatars_path      The path to the dir containing the game-avatars
   - @var    string  avatar            Filename of the avatar img
   - @var    int     width             The width of the avatar img (in pixels)
   - @var    int     height            The height of the avatar img (in pixels)
   - @var    int     faction           The faction of the character
   - @var    bool    function_override Return the results right after this, or continue?
   - @var    array   Array             with users profile field data

 Event name :  paybas.pbwow.modify_process_pf_after

 Description : * Event to modify the profile field processing script after the supported games are processed

 Placement : pbwow.process_pf_show

 Since 3.0.0

 Arguments :

  - @var   array   profile_row   Array with users profile field data
  - @var   array   tpl_fields    Array with template data fields
  - @var   string  avatars_path  The path to the dir containing the game-avatars
  - @var   string  avatar        Filename of the avatar img
  - @var   bool    valid         Whether an PF-value combination is valid (only used in certain cases)
  - @var   bool    avail         Whether an avatar is available (only used in certain cases)
  - @var   int     width         The width of the avatar img (in pixels)
  - @var   int     height        The height of the avatar img (in pixels)
  - @var   int     faction       The faction of the character

