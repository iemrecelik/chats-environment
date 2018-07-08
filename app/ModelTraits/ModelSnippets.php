<?php

namespace App\ModelTraits;

trait ModelSnippets
{
	public function convertCryptID(String $data){
        return crypt($data, 'axc10bstPwQds2');
    }
}