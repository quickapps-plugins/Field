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
namespace Field\Controller;

use Cake\Filesystem\File;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use CMS\Core\Plugin;
use Field\Utility\FileToolbox;

/**
 * Handles file uploading by "File Field Handler".
 *
 * @property \Field\Model\Table\FieldInstancesTable $FieldInstances
 */
class FileHandlerController extends AppController
{

    /**
     * Uploads a new file for the given FileField instance.
     *
     * @param string $name EAV attribute name
     * @throws \Cake\Network\Exception\NotFoundException When invalid slug is given,
     *  or when upload process could not be completed
     */
    public function upload($name)
    {
        $instance = $this->_getInstance($name);
        require_once Plugin::classPath('Field') . 'Lib/class.upload.php';
        $uploader = new \upload($this->request->data['Filedata']);

        if (!empty($instance->settings['extensions'])) {
            $exts = explode(',', $instance->settings['extensions']);
            $exts = array_map('trim', $exts);
            $exts = array_map('strtolower', $exts);

            if (!in_array(strtolower($uploader->file_src_name_ext), $exts)) {
                $this->_error(__d('field', 'Invalid file extension.'), 501);
            }
        }

        $response = '';
        $uploader->file_overwrite = false;
        $folder = normalizePath(WWW_ROOT . "/files/{$instance->settings['upload_folder']}/");
        $url = normalizePath("/files/{$instance->settings['upload_folder']}/", '/');

        $uploader->process($folder);
        if ($uploader->processed) {
            $response = json_encode([
                'file_url' => Router::url($url . $uploader->file_dst_name, true),
                'file_size' => FileToolbox::bytesToSize($uploader->file_src_size),
                'file_name' => $uploader->file_dst_name,
                'mime_icon' => FileToolbox::fileIcon($uploader->file_src_mime),
            ]);
        } else {
            $this->_error(__d('field', 'File upload error, details: {0}', $uploader->error), 502);
        }

        $this->viewBuilder()->layout('ajax');
        $this->title(__d('field', 'Upload File'));
        $this->set(compact('response'));
    }

    /**
     * Deletes a file for the given FileField instance.
     *
     * File name must be passes as `file` GET parameter.
     *
     * @param string $name EAV attribute name
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When invalid attribute name
     *  is given
     */
    public function delete($name)
    {
        $this->loadModel('Field.FieldInstances');
        $instance = $this->_getInstance($name);

        if ($instance && !empty($this->request->query['file'])) {
            $file = normalizePath(WWW_ROOT . "/files/{$instance->settings['upload_folder']}/{$this->request->query['file']}", DS);
            $file = new File($file);
            $file->delete();
        }

        $response = '';
        $this->viewBuilder()->layout('ajax');
        $this->title(__d('field', 'Delete File'));
        $this->set(compact('response'));
    }

    /**
     * Get field instance information.
     *
     * @param string $name EAV attribute name
     * @return \Field\Model\Entity\FieldInstance
     * @throws \Cake\Network\Exception\NotFoundException When invalid attribute name
     *  is given
     */
    protected function _getInstance($name)
    {
        $this->loadModel('Field.FieldInstances');
        $instance = $this->FieldInstances
            ->find()
            ->contain(['EavAttribute'])
            ->where(['EavAttribute.name' => $name])
            ->first();

        if (!$instance) {
            $this->_error(__d('field', 'Invalid field instance.'), 504);
        }

        return $instance;
    }

    /**
     * Sends a JSON message error.
     *
     * @param string $message The message
     * @param int $code A unique code identifier for this message
     * @return void Stops scripts execution
     */
    protected function _error($message, $code)
    {
        header("HTTP/1.0 {$code} {$message}");
        echo $message;

        TableRegistry::get('Field.FieldInstances')->connection()->disconnect();
        exit(0);
    }
}
