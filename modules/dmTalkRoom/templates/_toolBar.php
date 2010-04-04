<?php

echo
_tag('span.dm_talk_my_name', escape($speaker->name)).
_tag('button.dm_talk_button.dm_talk_change_nickname', __('Change nickname')).
_tag('button.dm_talk_button.dm_talk_invite_someone', __('Invite someone')).
_tag('button.dm_talk_button.dm_talk_save_conversation', __('Save conversation')).
//_tag('button.dm_talk_button.dm_talk_new_conversation', __('New conversation')).
_link($dm_page)->text(__('New conversation'))->target('blank')->set('.dm_talk_new_conversation')->title(false)->currentSpan(false);