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

<?php foreach ((array)$field->extra as $file): ?>
<p>
    <?php if (!empty($file['mime_icon'])): ?>
        <?= $this->Html->image(normalizePath("/field/img/file-icons/{$file['mime_icon']}", '/')); ?>
    <?php endif; ?>

    <?= $this->Html->link($file['file_name'], normalizePath("/files/{$field->settings['upload_folder']}/{$file['file_name']}", '/'), ['target' => '_blank']); ?>

    <?php if (!empty($file['description'])): ?>
        <em class="help-block"><?= $file['description']; ?></em>
    <?php endif; ?>
</p>
<?php endforeach; ?>
