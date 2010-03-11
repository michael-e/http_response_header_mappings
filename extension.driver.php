<?php

	Final Class extension_HTTP_Status_Line_Mappings extends Extension
	{
		public function about()
		{
			return array(
				'name' => 'HTTP (Response Header) Status Line Mappings',
				'version' => '1.0',
				'release-date' => '2010-03-10',
				'author'       => array(
					'name'    => 'Michael Eichelsdoerfer',
					'website' => 'http://www.michael-eichelsdoerfer.de',
					'email'   => 'info@michael-eichelsdoerfer.de'
				)
			);
		}

		public function install()
		{
			$initial_mappings = array(
				'503' => 'HTTP/1.1 503 Service Unavailable'
			);
			foreach($initial_mappings as $status => $header)
			{
				Administration::instance()->Configuration->set($status, $header, 'http-status-line-mappings');
			}
			$this->_Parent->saveConfig();
		}

		public function uninstall()
		{
			Administration::instance()->Configuration->remove('http-status-line-mappings');
			Administration::instance()->saveConfig();
		}

		public function resolveStatus($status)
		{
			return $this->_Parent->Configuration->get($status, 'http-status-line-mappings');
		}

		public function getSubscribedDelegates()
		{
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendPreRenderHeaders',
					'callback' => 'setContentType'
				),
			);
		}

		public function setContentType(array $context=NULL)
		{
			$page_data = Frontend::Page()->pageData();
			if(!isset($page_data['type']) || !is_array($page_data['type']) || empty($page_data['type'])) return;

			foreach($page_data['type'] as $status)
			{
				$header = $this->resolveStatus($status);
				if(!is_null($header))
				{
					Frontend::Page()->addHeaderToPage($header);
				}
			}
		}
	}
