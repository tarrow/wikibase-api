<?php

namespace Wikibase\Api\Repos;

use DataValues\Deserializers\DataValueDeserializer;
use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\DataModel\EntityRevision;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\SiteLink;

class EntityRevisionRepo {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var Deserializer
	 */
	protected $entityDeserializer;

	public function __construct( MediawikiApi $api, Deserializer $entityDeserializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
	}

	/**
	 * @param string|EntityId $id
	 * @returns EntityRevision
	 */
	public function getFromId( $id ) {
		if( $id instanceof EntityId ) {
			$id = $id->getPrefixedId();
		}

		return $this->newEntityRevisionFromResult( $this->getEntityResultById( $id ) );
	}
	
	/**
	 * @param string $id
	 * @return array
	 */
	private function getEntityResultById( $id ) {
		$result = $this->api->getAction( 'wbgetentities', array( 'ids' => $id ) );
		return array_shift( $result['entities'] );
	}
	
	/**
	 * @param array $entityResult
	 * @returns Entity
	 */
	private function newEntityRevisionFromResult( array $entityResult ) {
		return new EntityRevision(
			$this->entityDeserializer->deserialize( $entityResult ),
			$entityResult['lastrevid']
		);
	}

	/**
	 * @param string|SiteLink $siteLink
	 * @returns EntityRevision
	 */
	public function getFromSiteLink( $siteLink ) {
		//TODO
	}

}