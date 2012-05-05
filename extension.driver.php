<?php

	Final Class extension_HTTP_Response_Header_Mappings extends Extension{

		const SETTINGS_GROUP = 'http-response-header-mappings';
		const EXT_NAME = 'HTTP Response Header Mappings';

		public function about(){
			return array(
				'name' => self::EXT_NAME,
				'version' => '1.0',
				'release-date' => '2012-03-17',
				'author'       => array(
					'name'    => 'Michael Eichelsdoerfer',
					'website' => 'http://www.michael-eichelsdoerfer.de',
					'email'   => 'info@michael-eichelsdoerfer.de'
				)
			);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendPreRenderHeaders',
					'callback' => 'setHeader'
				),
			);
		}

		public function install(){
			$initial_mappings = array(
				'503'   => 'HTTP/1.1 503 Service Unavailable',
				'ra1h'  => 'Retry-After: 3600',
				'ra3h'  => 'Retry-After: 10800',
				'ra24h' => 'Retry-After: 86400',
				'xml'   => 'Content-Type: text/xml; charset=utf-8',
				'txt'   => 'Content-Type: text/plain; charset=utf-8',
				'js'    => 'Content-Type: application/x-javascript; charset=utf-8',
				'css'   => 'Content-Type: text/css; charset=utf-8',
				'csv'   => 'Content-Type: text/comma-separated-values; charset=utf-16',
				'xls'   => 'Content-Type: application/msexcel',
				'rss'   => 'Content-Type: application/rss+xml',
				'text'  => 'Content-Type: text/plain',
			);

			foreach($initial_mappings as $page_type => $response_header){
				Symphony::Configuration()->set($page_type, $response_header, self::SETTINGS_GROUP);
			}
			Administration::instance()->saveConfig();
		}

		public function uninstall(){
			Symphony::Configuration()->remove(self::SETTINGS_GROUP);
			Administration::instance()->saveConfig();
		}

		public function resolveHeader($page_type){
			return Symphony::Configuration()->get($page_type, self::SETTINGS_GROUP);
			if($page_type{0} == '.'){
				return Symphony::Configuration()->get(substr($page_type, 1), self::SETTINGS_GROUP);
			}
			else{
				return Symphony::Configuration()->get($page_type, self::SETTINGS_GROUP);
			}
		}

		public function setHeader(array $context=NULL){
			$page_data = Frontend::Page()->pageData();
			if(!isset($page_data['type']) || !is_array($page_data['type']) || empty($page_data['type'])) return;

			foreach($page_data['type'] as $page_type){
				$page_type = strtolower($page_type);
				$response_header = $this->resolveHeader($page_type);

				if(!is_null($response_header)){
					Frontend::Page()->addHeaderToPage($response_header);
				}
				if($page_type{0} == '.'){
					$file_name = $page_data['handle'];
					Frontend::Page()->addHeaderToPage('Content-Disposition', "attachment; filename={$file_name}{$page_type}");
				}
			}
		}
	}
