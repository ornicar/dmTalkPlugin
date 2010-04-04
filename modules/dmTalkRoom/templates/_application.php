<?php

use_stylesheet('dmTalkPlugin.style');
use_javascript('dmTalkPlugin.ctrl');

// show only connection form to users that don't have a nickname yet
if(isset($connectionForm))
{
  return include_partial('dmTalkRoom/connection', array('form' => $connectionForm));
}

// pass json metadata to the JavaScript
$json = array(
  'invite_someone_message' => __('Give this url to invite someone to join the room: %url%', array(
    '%url%' => _link($dm_page)->param('r', $room->code)->getAbsoluteHref())
  ),
  'change_nickname_message' => __('Please enter your nickname'),
  'change_nickname_url' => _link('+/dmTalkRoom/changeNickname')->param('s', $speaker->code)->getHref(),
  'save_conversation_message' => __('Keep this url to go back to this room later: %url%', array(
    '%url%' => _link($dm_page)->param('s', $speaker->code)->getAbsoluteHref())
  ),
  'speaker_code' => $speaker->code,
  'refresh_url' => _link('+/dmTalkRoom/refresh')->param('s', $speaker->code)->getHref(),
  'new_conversation_url' => _link($dm_page)->getHref(),
  'ajax_refresh_delay' => sfConfig::get('dm_talk_ajax_refresh_delay', 2000)
);

echo _tag('div.dm_talk', array('json' => $json),

  _tag('div.dm_talk_tool_bar_wrap', _tag('div.dm_talk_tool_bar.clearfix',
    get_partial('dmTalkRoom/toolBar', array('speaker' => $speaker))
  )).

  _tag('div.dm_talk_content.clearfix',

    _tag('div.dm_talk_room_wrap', _tag('div.dm_talk_room',
      _tag('div.dm_talk_conversation_wrap', _tag('div.dm_talk_conversation')).
      _tag('div.dm_talk_new_message_wrap', get_partial('dmTalkRoom/newMessage', array(
        'room'    => $room,
        'speaker' => $speaker
      )))
    )).

    _tag('div.dm_talk_side',
      _tag('div.dm_talk_speakers_wrap', _tag('div.dm_talk_speakers'))
    )
  )
);