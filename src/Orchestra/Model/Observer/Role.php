<?php namespace Orchestra\Model\Observer;

use Orchestra\Support\Facades\Acl;

class Role {

	/**
	 * On creating observer.
	 *
	 * @param  \Orchestra\Model\Role    $model
	 * @return void
	 */
	public function creating($model)
	{
		Acl::addRole($model->getAttribute('name'));
	}

	/**
	 * On deleting observer.
	 * 
	 * @param  \Orchestra\Model\Role    $model
	 * @return void
	 */
	public function deleting($model)
	{
		Acl::removeRole($model->getAttribute('name'));
	}

	/**
	 * On updating/restoring observer.
	 * 
	 * @param  \Orchestra\Model\Role    $model
	 * @return void
	 */
	public function updating($model)
	{
		$originalName = $model->getOriginal('name');
		$currentName  = $model->getAttribute('name');
		$deletedAt    = $model->getDeletedAtColumn();

		if ($model->isSoftDeleting() 
			and is_null($model->getAttribute($deletedAt)) 
			and ! is_null($model->getOriginal($deletedAt)))
		{
			Acl::addRole($currentName);
		}
		else 
		{
			Acl::renameRole($originalName, $currentName);
		}
	}
}
