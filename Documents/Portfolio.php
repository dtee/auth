<?php
namespace Odl\AuthBundle\Documents;

/**
 * @mongodb:Document(db="user", collection="user_portfolio")
 */
class Portfolio
{
	/**
	 * @mongodb:id
	 */
	public $id;

	/**
	 * @mongodb:Field(type="string", name="_id")
	 */
	public $username;

	/** @mongodb:Field(type="hash") */
	public $location;

	/**
	 * @mongodb:Field(type="date", name="create_time")
	 */
	public $createTime;

	/**
	 * @mongodb:Field(type="int", name="create_time")
	 */
	public $userId;
}
