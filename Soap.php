<?php
/**
 * Created by PhpStorm.
 * author: Zuojg / 左俊光
 * email: rommel.zuo@gmail.com
 * Date: 2017/11/17
 * Time: 9:30
 * SOAP接口操作类,链式操作 ,例如 : $soap->setWsdUrl($url)->client()->getFunctins();
 */

class Soap
{
    private $wsdUrl = '';
    private $soapClientOptions = array();
    private $server = null;
    private $result = null;
    public function __construct()
    {
        $this->setWsdUrl();
        $this->setSoapClientOptions();
    }

    /**
     * 设置接口地址（目前默认公司自用LIS）
     * @param null $url 设置的接口地址
     * @return $this
     */
    public function setWsdUrl( $url = null)
    {
        if ( !$url ) {
            $this->wsdUrl = 'http://116.213.144.25:8112/WeblisInterface/Service/WeblisService.asmx?WSDL';
        } else {
            $this->wsdUrl = ( substr( trim( $url ) , -4 ) == 'WSDL' ) ? trim( $url ) : trim( $url ) . '?WSDL';
        }
        return $this; 
    }

    public function setSoapClientOptions()
    {
        $this->soapClientOptions = ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY,'encoding' => 'UTF-8'];
        return $this;
    }

    /**
     * 创建SOAP连接
     * @return $this
     */
    public function client()
    {
        try{
           $this->server =  new SoapClient( $this->wsdUrl , $this->soapClientOptions );
           return $this;
        } catch ( SOAPFault $e ) {
            echo $e->getMessage();
        }
    }

    /**
     * 获取接口下所有可用的方法
     * @return mixed
     */
    public function getFunctions()
    {
        return $this->server->__getFunctions();
    }


    public function getResult()
    {
        $p = xml_parser_create();
        xml_parse_into_struct($p,$this->result,$index,$values);
        xml_parser_free($p);
        return $values;
    }
}
$wsdUrl = 'http://116.213.144.25:8112/WeblisInterface/Service/WeblisService.asmx';
$soap = new Soap();
var_dump( $soap->setWsdUrl($wsdUrl)->client()->getFunctions() );



