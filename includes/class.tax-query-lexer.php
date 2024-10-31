<?php

/**
 * This class allow to parse a string to build a tax_query (an array) that can be used to query post
 * with Wordpress object or method like "WP_query" or "get_posts"
 *
 * The string can be parsed if it follows a specific syntax.
 *
 * TODO:
 * - For now the TaxQueryLexer only handle simple rules like : taxonomy_name=my_term
 * - In the future we want to allow several rules that can be nested :
 * ex : "taxonomy_1=term1 OR (taxonomy_2=term2 AND taxonomy_3=term3)"
 *
 * Class TaxQueryLexer
 */


class TaxQueryLexer {

    public function __construct(){

    }

    public function build_tax_query($rule){
        $separator = "=";
        $result_array = preg_split( "/($separator)/", $rule, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

        // The rule mus be valid (containing an operator and 2 operands)
        if(count($result_array) == 3)
        {
            return [
                [
                    'taxonomy' => $result_array[0],
                    'field'    => 'slug',
                    'terms'    => array( $result_array[2] ),
                    'operator' => 'IN'
                ]
            ];
        }
        return false;
    }

}