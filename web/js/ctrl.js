(function($)
{
  $(function()
  {
    $('div.dm_talk').each(function()
    {
      var
      $this         = $(this),
      metadata      = $(this).metadata(),
      $toolbar      = $this.find('div.dm_talk_tool_bar'),
      $conversation = $this.find('div.dm_talk_conversation'),
      $speakers     = $this.find('div.dm_talk_speakers'),
      $form         = $this.find('form.dm_talk_new_message'),
      $input        = $form.find('input[name=text]').focus(),
      hash          = 0,
      refresh       = function()
      {
        $.ajax({
          dataType: 'json',
          url:      metadata.refresh_url,
          data:     { hash:  hash },
          success:  function(data)
          {
            if(data.hash)
            {
              hash = data.hash;
              $conversation.html(data.conversation)[0].scrollTop = 9999999;
            }
            $speakers.html(data.speakers);
            setTimeout(refresh, metadata.ajax_refresh_delay);
          }
        });
      };

      // styling
      $conversation.height($conversation.parent().height()-2);
      $form.height($form.parent().height()-2);
      $speakers.height($speakers.parent().height()-2);
      $input.width($conversation.width()-10);

      // initialize and run
      refresh();

      // send a message
      $form.submit(function()
      {
        if(text = $.trim($input.val()))
        {
          $input.val('');
          $.post($form.attr('action'), { text: text }, function(conversation)
          {
            $conversation.html(conversation)[0].scrollTop = 9999999;
          });
        }

        return false;
      });

      // change nickname
      $toolbar.find('button.dm_talk_change_nickname').click(function()
      {
        if(nickname = prompt(metadata.change_nickname_message))
        {
          $.post(metadata.change_nickname_url, { nickname: nickname }, function(message)
          {
            if(message != 'ok')
            {
              alert(message);
            }
            else
            {
              $toolbar.find('.dm_talk_my_name').text(nickname);
            }
          });
        }
      });

      // send an invitation
      $toolbar.find('button.dm_talk_invite_someone').click(function()
      {
        alert(metadata.invite_someone_message);
      })

      // save the conversation
      $toolbar.find('button.dm_talk_save_conversation').click(function()
      {
        alert(metadata.save_conversation_message);
      });
    });
  });
})(jQuery);