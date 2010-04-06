(function($)
{
  $(function()
  {
    $('div.dm_talk').each(function()
    {
      var
      $this           = $(this),
      metadata        = $(this).metadata(),
      $toolbar        = $this.find('div.dm_talk_tool_bar'),
      $conversation   = $this.find('div.dm_talk_conversation'),
      $speakers       = $this.find('div.dm_talk_speakers'),
      $form           = $this.find('form.dm_talk_new_message'),
      $input          = $form.find('input[name=text]').focus(),
      hash            = 0,
      isActive        = false,
      isActiveTimeout = null,
      isUnread        = false,
      initialTitle    = document.title,
      refreshTimeout  = null,
      refreshRequest  = null,
      refresh         = function()
      {
        refreshRequest = $.get(metadata.refresh_url, { hash:  hash }, function(data)
        {
          if(data.hash)
          {
            updateConversation(data.hash, data.html);
            isUnread = !isActive;
          }
          (data.speakers != $speakers.html()) && $speakers.html(data.speakers);
          reset();
        }, 'json');
      },
      updateConversation = function(newHash, newHtml)
      {
        hash = newHash;
        $conversation.html(newHtml)[0].scrollTop = 9999999;
      },
      reset = function()
      {
        refreshRequest && refreshRequest.abort();
        refreshTimeout && clearTimeout(refreshTimeout);
        refreshTimeout = setTimeout(refresh, metadata.ajax_refresh_delay);
      };

      // styling
      $conversation.height($conversation.parent().height()-2);
      $form.height($form.parent().height()-2);
      $speakers.height($speakers.parent().height()-2);
      $input.width($conversation.width()-10);

      // detect presence
      $(document).bind('mousemove keydown', function()
      {
        isUnread = false;
        isActive = true;
        isActiveTimeout = setTimeout(function() { isActive = false; }, 500);
      }).trigger('mousemove');

      // update document title to show unread state
      setInterval(function()
      {
        document.title = isUnread
        ? document.title = document.title == initialTitle || document.title.indexOf('/\\/') == 0
          ? '\\/\\ '+initialTitle
          : '/\\/ '+initialTitle
        : initialTitle;
      }, 500);

      // run
      refresh();

      // send a message
      $form.submit(function()
      {
        if(text = $.trim($input.val()))
        {
          $input.val('');
          $.post($form.attr('action'), { text: text }, function(data)
          {
            updateConversation(data.hash, data.html);
            reset();
          }, 'json');
        }
        return false;
      });

      // change nickname
      $toolbar.find('button.dm_talk_change_nickname').click(function()
      {
        if(nickname = $.trim(prompt(metadata.change_nickname_message)))
        {
          $.post(metadata.change_nickname_url, { nickname: nickname }, function(msg)
          {
            msg == 'ok' ? $toolbar.find('.dm_talk_my_name').text(nickname) : alert(msg);
          });
        }
      });

      // send an invitation
      $toolbar.find('button.dm_talk_invite_someone').click(function()
      {
        $.get(metadata.invite_someone_url, function(data)
        {
          updateConversation(data.hash, data.html);
          reset();
        }, 'json');
      });

      // save the conversation
      $toolbar.find('button.dm_talk_save_conversation').click(function()
      {
        $.get(metadata.save_conversation_url, function(data)
        {
          updateConversation(data.hash, data.html);
          reset();
        }, 'json');
      });
    });
  });
})(jQuery);