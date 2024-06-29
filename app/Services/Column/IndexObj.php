<?php

namespace App\Services\Column;

class IndexObj
{

    public $indexs;
    public $primary;

    public function __construct(string $createSentence)
    {
        $arr2 = null;
        preg_match_all('#^\s+(UNIQUE |PRIMARY )?KEY.+?$#m', $createSentence, $arr2);
        $this->setIndex( $arr2[0] );

        $this->setPrimary('');
        if ( $this->getIndex() ) {
            foreach ($this->getIndex() as $v2) {
                if (preg_match('#PRIMARY#', $v2)) {
                    $primary = preg_replace('#^.*?\((.+?)\).*$#', '$1', $v2);
                    $primary = trim($primary, '`');
                    $this->setPrimary($primary);
                }
            }
        } else {
            $this->setIndex( [] );
        }

    }

    public function getArray()
    {
        return (array)$this;

    }

    protected function setIndex($index)
    {
        $this->indexs = $index;
    }

    protected function setPrimary($primary){
        $this->primary = $primary;
    }

    protected function getIndex(){
        return $this->indexs;
    }

}
