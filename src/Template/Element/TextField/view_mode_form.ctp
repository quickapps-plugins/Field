<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?=
    $this->Form->input('formatter', [
        'id' => 'display-type-selectbox',
        'label' => __d('field', 'Display content as'),
        'type' => 'select',
        'options' => array(
            'plain' => __d('field', 'Plain'),
            'full' => __d('field', 'Full'),
            'trimmed' => __d('field', 'Trimmed')
        ),
        'empty' => false,
        'escape' => false,
        'onchange' => "if (this.value == 'trimmed') { $('#trimmed').show(); } else { $('#trimmed').hide(); };"
    ]);
?>

<ul>
    <li><em class="help-block"><strong><?= __d('field', 'Full'); ?>:</strong> <?= __d('field', 'Text will be rendered with no modifications.'); ?></em></li>
    <li><em class="help-block"><strong><?= __d('field', 'Plain'); ?>:</strong> <?= __d('field', 'Text will converted to plain text.'); ?></em></li>
    <li><em class="help-block"><strong><?= __d('field', 'Trimmed'); ?>:</strong> <?= __d('field', 'Text will cut to an specific length.'); ?></em></li>
</ul>

<div id="trimmed">
    <?= $this->Form->input('trim_length', ['type' => 'text', 'label' => __d('field', 'Trim length or read-more-cutter')]); ?>

    <ul>
        <li><em class="help-block"><?= __d('field', 'Numeric value will convert content to plain text and then trim it to the specified number of chars. e.g.: 400'); ?></em></li>
        <li><em class="help-block"><?= __d('field', 'String value will cut the content in two by the specified string, the first part will be displayed. e.g.: &lt;!-- readmore --&gt;'); ?></em></li>
    </ul>
</div>

<script type="text/javascript">
    if ($('#display-type-selectbox').val() == 'trimmed') {
        $('#trimmed').show();
    } else {
        $('#trimmed').hide();
    }
</script>