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
use Field\Utility\DateToolbox;

$dateFormat = empty($field->metadata->settings['format']) ? 'yy-mm-dd' : $field->metadata->settings['format'];
$timeFormat = empty($field->metadata->settings['time_format']) ? '' : $field->metadata->settings['time_format'];
$timestamp = $field->value ? $field->value->getTimestamp() : 0;
$displayDate = DateToolbox::formatDate("{$dateFormat} {$timeFormat}", $timestamp);
?>

<?php if ($field->viewModeSettings['label_visibility'] == 'above'): ?>
    <h3 class="field-label"><?= $field->label; ?></h3>
    <p><?= $displayDate; ?></p>
<?php elseif ($field->viewModeSettings['label_visibility'] == 'inline'): ?>
    <p><strong class="field-label"><?= $field->label; ?>:</strong> <?= $displayDate; ?></p>
<?php else: ?>
    <p><?= $displayDate; ?></p>
<?php endif; ?>
