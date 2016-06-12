<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\DependencyInjection\Configuration
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('m_kosolofski_house_seats_monitor');

		$rootNode->children()
			->scalarNode('admin_email')
				->isRequired()
				->cannotBeEmpty()
			->end()
            ->scalarNode('cookie_file')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
			->scalarNode('domain')
				->isRequired()
				->cannotBeEmpty()
			->end()
			->arrayNode('login')
				->isRequired()
				->children()
					->scalarNode('email')
						->isRequired()
						->cannotBeEmpty()
					->end()
					->scalarNode('password')
						->isRequired()
						->cannotBeEmpty()
					->end()
					->integerNode('max_attempts')
						->defaultValue(3)
					->end()
				->end()
			->end()
            ->arrayNode('page')
                ->addDefaultsIfNotSet()
				->children()
					->scalarNode('login')
                        ->cannotBeEmpty()
                        ->defaultValue('/member/index.bv')
					->end()
					->scalarNode('active_shows')
                        ->cannotBeEmpty()
                        ->defaultValue('/member/ajax.bv?supersecret=&search=&sortField=showTime&startMonthYear=&endMonthYear=&startDate=&endDate=&start=0')
					->end()
				->end()
			->end()
			->arrayNode('notify')
				->children()
                    ->arrayNode('emails')
                        ->prototype('scalar')->end()
					->end()
				->end()
			->end()
		->end();

		return $treeBuilder;
    }
}
