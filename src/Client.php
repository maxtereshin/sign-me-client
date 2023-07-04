<?php

namespace Maxtereshin\SignMeClient;

use Exception;
use Maxtereshin\SignMeClient\BaseClient\Client as BaseClient;
class Client
{

    private BaseClient $client;
    private string $base_url;

    public function __construct()
    {
        $config = config('signmeclient');
        if(!$config) {
            $config = include __DIR__ . '/../config/signmeclient.php';
        }
        $config['debug'] ? $url = $config['test_url'] : $url = $config['base_url'];
        $this->client = new BaseClient($url, $config['api_key']);
        $this->base_url = $config['base_url'];
    }

    public function register($user_data) {
        return $this->client->request('register/api/', $user_data);
    }

    public function checkRegister($phone) {
        return $this->client->request('register/precheck/', [ 'phone' => $phone ]);
    }

    public function activate($id) {
        return $this->client->request('register/precheck/', [ 'uid' => $id ]);
    }

    public function userInfo($phone) {
        return $this->client->request('register/userinfo/', [ 'phone' => $phone ]);
    }

    public function certificate($get_active_certs, $get_all_certs, $format = 1, $phone = null, $ogrn = null) {
        $data = [
            'get_active_certs' => $get_active_certs,
            'get_all_certs'=> $get_all_certs,
            'format'=> $format
        ];
        if($phone) {
            $data['user_ph'] = $phone;
        } else {
            $data['company_ogrn'] = $ogrn;
        }
        return $this->client->request('sign/cer/', $data, false);
    }

    /**
     * Подпись файла
     * @param $file
     * @param $filename
     * @param $email
     * @param $phone
     * @param bool $noemail
     * @param bool $nopush
     * @return string
     * @throws Exception
     */
    public function signFile($file, $filename, $email, $phone, bool $noemail = true, bool $nopush = false): string
    {
        $data = [
            'filet' => base64_encode($file),
            'fname' => $filename,
            'user_email' => $email,
            'user_ph' => $phone,
        ];
        if($noemail) {
            $data['noemail'] = 1;
        }
        if($nopush) {
            $data['nopush'] = 1;
        }
        $part = $this->client->request('signapi/sjson', $data);
        return $this->base_url . 'signapi/sjson/' . $part;
    }

    /**
     * Подпись нескольких файлов
     * @param $files
     * @param $email
     * @param $phone
     * @param bool $noemail
     * @param bool $nopush
     * @return string
     * @throws Exception
     */
    public function signFiles($files, $email, $phone, bool $noemail = true, bool $nopush = false): string
    {
        $data = [
            'files' => $files,
            'multiname ' => 'protocols',
            'noemail ' => 1,
            'user_email' => $email,
            'user_ph' => $phone,
        ];
        if($noemail) {
            $data['noemail'] = 1;
        }
        if($nopush) {
            $data['nopush'] = 1;
        }
        $part = $this->client->request('signapi/multijson', $data);
        return $this->base_url . 'signapi/multijson/' . $part;
    }

    public function checkSignFileJson($file) {
        $data = [
            'filet' => base64_encode($file),
            'md5' => md5($file),
        ];
        return $this->client->request('signaturecheck/json', $data);
    }

    public function checkSignFileRequest($file) {
        $data['hash'] = md5($file);
        return $this->client->request('signaturecheck/request', $data, false);
    }

}