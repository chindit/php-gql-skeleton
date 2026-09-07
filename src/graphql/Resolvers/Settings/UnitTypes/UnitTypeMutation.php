<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation
{
	public static function get(): array
	{
		return [
			'unitTypeCreate' => [
				'type' => new NonNull(Types::get(UnitType::class)),
				'args' => [
					'input' => new NonNull(
						Types::get(UnitTypeCreateInput::class)
					),
				],
				'resolve' => function (
					$root,
					$args,
					RequestContext $context
				) {
					return $context->useCases
						->unitType
						->unitTypeCreate
						->handle($args['input']['name']);
				},
			],
		];
	}
}
