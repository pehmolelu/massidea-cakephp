<ul>
    <li><?php echo $this->Html->link(
                               $html->image('icon_message_off.png',array('class' => 'middle', 'id'=>'compose_message_link')).
                                            'Compose Message',
                               array('controller'=>'private_messages', 'action'=>'browse'),
                               array('escape' => false)); ?>

        <div class="dot-line-200"> </div>
    </li>

    <li class="margin-top small-padding-top-bottom">
        <div class="hoverLink">
                <?php echo $this->Html->link('Inbox '.'<span class="grey">('.count($messages_in).') </span>',
                               '#',
                               array('escape' => false)); ?>
        </div>
    </li>
    <li class="margin-top small-padding-top-bottom">
        <div class="hoverLink">
                <?php echo $this->Html->link('Notifications '.'<span class="grey">('.count($messages).') </span>',
                               '#',
                               array('escape' => false)); ?>
        </div>
    </li>
    <li class="margin-top small-padding-top-bottom">
        <div class="hoverLink">
                <?php echo $this->Html->link('Sent'.'<span class="grey">('.count($messages_out).') </span>',
                               '#',
                               array('escape' => false)); ?>
        </div>
    </li>

</ul>
