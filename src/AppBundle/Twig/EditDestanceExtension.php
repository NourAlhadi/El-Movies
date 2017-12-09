<?php


namespace AppBundle\Twig;


class EditDestanceExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('heMeans', array($this, 'heMeans')),
        );
    }

    private $s,$t,$n,$m;
    private $memo;

    public function editDistance($i,$j){
        if ($i == $this->n) return $this->m - $j;
        if ($j == $this->m) return $this->n - $i;

        $val = $this->memo[$i][$j];
        if ($val != -1) return $val;
        if ($this->s[$i] == $this->t[$j]) return $this->editDistance($i+1,$j+1);
        $this->memo[$i][$j] = 10000;

        $ans1 = $this->editDistance($i+1,$j+1) + 1;
        $ans2 = $this->editDistance($i+1,$j) + 1;
        $ans3 = $this->editDistance($i,$j+1) + 1;

        $this->memo[$i][$j] = min($ans1,$ans2,$ans3);

        return $this->memo[$i][$j];
    }

    public function init(){
        $this->memo = array();
        for ($i = 0; $i < $this->n; ++$i){
            $this->memo[$i] = array();
            for ($j = 0; $j < $this->m; ++$j){
                $this->memo[$i][$j] = -1;
            }
        }
    }

    public function heMeans($str1,$str2){
        $this->s = $str1; $this->n = strlen($this->s);
        $this->t = $str2; $this->m = strlen($this->t);
        $this->init();
        return $this->editDistance(0,0);
    }

}