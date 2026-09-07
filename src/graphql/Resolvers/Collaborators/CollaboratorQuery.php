<?php

namespace Vertuoza\Api\Graphql\Resolvers\Collaborators;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CollaboratorQuery
{
	static function get()
	{
		return [
			'collaborators' => [
				'type' => new NonNull(
					new ListOfType(
						new NonNull(Types::get(Collaborator::class))
					)
				),
				'resolve' => function($root, $args, RequestContext $context) {
					return $context->useCases->collaborators->collaboratorsFindMany->handle();
				}
			],
			'collaboratorById' => [
				'type' => new NonNull(Types::get(Collaborator::class)),
				'args' => [
					'id' => new NonNull(Types::id()),
				],
				'resolve' => function ($root, $args, RequestContext $context) {
					return $context->useCases->collaborators->collaboratorById->handle($args['id']);
				}
			]
		];
	}
}
