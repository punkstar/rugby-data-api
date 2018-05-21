<?php

namespace Punkstar\RugbyFeed;

class Team
{
//    /**
//     * @param $name
//     * @return Team
//     */
//    public static function build($name)
//    {
//        foreach (self::$team_map as $team_key => $team_data) {
//            $team_aliases = array_map('strtolower', $team_data['aliases']);
//
//            if (in_array(strtolower($name), $team_aliases)) {
//                return new self($team_key);
//            }
//        }
//
//        return new self($name);
//    }

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getUrlKey()
    {
        return $this->data['url'] ?? str_replace(' ', '_', strtolower($this->getName()));
    }
    
    /**
     * @param $searchString
     * @return bool
     */
    public function isAliasedTo($searchString)
    {
        $aliases = array_map('strtolower', $this->data['alias'] ?? []);
        $aliases[] = strtolower($this->getName());
        $aliases[] = strtolower($this->getUrlKey());
        
        return in_array(strtolower($searchString), $aliases);
    }
    
    public function getName()
    {
        return $this->data['name'] ?? 'Unknown Name';
    }

    /**
     * @return string
     */
    public function getStadium()
    {
        return $this->data['stadium'] ?? '';
    }
//
//    /**
//     * @param $alias
//     * @return bool
//     */
//    public function isTeamAlias($alias)
//    {
//        return in_array(strtolower($alias), $this->getTeamAliases(), true);
//    }
//
//    /**
//     * Get lowercased valid team aliases.
//     *
//     * @return array
//     */
//    public function getTeamAliases()
//    {
//        $aliases = static::$team_map[$this->key]['aliases'];
//        $aliases[] = $this->key;
//        $aliases[] = $this->name;
//
//        return array_unique(array_map('strtolower', $aliases));
//    }
}
