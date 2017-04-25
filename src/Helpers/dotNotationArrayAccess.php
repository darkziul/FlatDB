<?php
 
namespace darkziul\Helpers;

/**
 * A simple and secure helper to manipulete Array in various ways via dot notation
 * PHP dot notation array access
 * @author Luiz Carlos Wagner
 * @license MIT
 **/


// FEITO teste entre EVAL e FOREACH, EVAL foi mais rapido =====
// ============================================================


// STRING colchete  key[subjey] ou [key][subkey]  


class arrayAccessException extends \Exception{}

class dotNotationArrayAccess
{

	/**
	 * Converte dot em array
	 * @param string $dot String not notation @example main.category.name ;  main.[+].name 
	 * @return array
	 */
	public static function dotToArray($dot)
	{
		return is_string($dot) ? explode('.', $dot) : $dot;
	}


	/**
	 * Atualizacao rigorosa. Atualiza apenas caso exista o valor mencionado para comparacao
	 * @param array &$array Array Base 
	 * @param array $keyAndValueAndValueCompare Array com o seletor[valorAtual => valorNovo]. Caso o valor Atual existir no seletor, sera substituido por valorNovo 
	 * @return array Array base modificado
	 */
	public static function changeStrict(array &$array, array $keyAndValueAndValueCompare)
	{
		return self::change($array,$keyAndValueAndValueCompare,'',true);
	}
	/**
	 * Atualizar
	 * @param array &$array ARRAY base para consultada 
	 * @param type $keys Chave ou conjunto de chaves&valores 
	 * @param type|null $value Valor que ira substiuir. Caso $keys for um conjunto, nao seta-lo
	 * @param bool $compare TRUE comparar valor , se o valor existir sera substituido. FALSE substituicao sem comparacao
	 * @return Array Array base modificado
	 */
	public static function change(array &$array, $keys, $value=null, $compare=false) {

			if(is_array($keys)) {
				foreach ($keys as $k => $v) {
					self::change($array, $k, $v, $compare);
				}
			} else {
				self::__change__($array, self::dotToArray($keys), $value, 'change', $compare);
			}

			return $array;
	}

	public static function remove(array &$array, $keys, $value=null)
	{
		if (is_array($keys)) {
			foreach ($keys as $k => $v) {
				if(is_numeric($k)) self::remove($array, $v, $value);
				else self::remove($array, $k, $v);
			}
		} else {
			self::__change__($array, self::dotToArray($keys), $value, 'remove');
		}

		return $array;
	}

	


	/**
	 * Saber se existi chave, chave com valor, ou grupo dos ambos casos
	 * @param array $array Array base para consulta
	 * @param mixed $keys Array de chaves/chaves+valor/ou misto @example 'item' , ['item'=>15, 'item.code']  
	 * @param type|null $value Caso for setado sera usado para comparacao tambem. Caso for usado array em $keys nao seta-lo
	 * @return bool TRUE se existir, FALSE caso ao contrario
	 */
	public static function exists(array $array, $keys, $value=null)
	{

		if (is_array($keys)) {

			foreach ($keys as $key => $value) {
				if (is_numeric($key)) {
					$result = self::exists($array, $value);
				} else {
					$result = self::exists($array, $key, $value);
				}

				if (!$result) return false;

			}

			return true; // ok, existi

		} else {
			$data = self::__get__($array, self::dotToArray($keys), false, true, $value);
			return (bool)$data;
		}

	}
	/**
	 * Obter
	 * @param array $array Array base
	 * @param type|null $keys chave/chaves de busca
	 * @param type|bool $getKeyName TRUE o resultado terá o nome da chave <code>['main.code' => 15]</code>, FALSE não mostrrá o nome <code>[0 => 15]</code>
	 * @return Mi
	 */
	public static function get(array $array, $keys=null, $getKeyName=false)
	{
		if (!isset($keys)) return $array;
		$keys = (array) $keys;
		$count = count($keys);
		$result = [];
		foreach ($keys as $key) {
			if ($getKeyName) $result[$key] = self::__get__($array, self::dotToArray($key));
			else $result[] = self::__get__($array, self::dotToArray($key));
			
		}

		return  (($count-1) || $getKeyName) ?  $result : $result[0];
		// return array_filter($result);
	}

	public static function create(&$keyAndValue, $value=null, &$array = [])
	{
		if (is_array($keyAndValue)) {

			foreach ($keyAndValue as $key => $value) {
				self::create($key, $value, $array);
			}
		} else {
			$keys = self::dotToArray($keyAndValue);
			$count = count($keys)-1;
			// $array = [];//init
			for ($i=0; $i < $count ; $i++) { 
				$index = $keys[$i];
				if (!isset($array[$index])) $array[$index] = [];
				$array = &$array[$index];
			}
			$array[$keys[$i]] = $value;
		}

		return $keyAndValue = $array;
	}

	/**
	 * Inserir conteudo
	 * @param type &$array 
	 * @param type $keys ['main.a','main.b'] / ['main.a'=>'1540', 'main.b'] / 'a' / nunca usar numeros como chaves [main.14=>'hello']
	 * @param type|null $value 
	 * @param type|bool $valueMerge TRUE Caso o seletor for string irá convete o valor em array e add o elemento de $value, Força a adição em string. FALSE igora string
	 * @return type
	 */
	public static function insert(array &$array, $keys, $valueMerge=false, $setExists=false, $value='')
	{

		if (is_array($keys)) {
			foreach ($keys as $k => $v) {
				if (is_numeric($k)) self::insert($array, $v, $valueMerge, $setExists, $value);
				else self::insert($array, $k, $valueMerge, $setExists, $v);
			}
		} else {
			self::__set__($array, self::dotToArray($keys), $valueMerge, $setExists, $value);
		}

		return $array;

	}

	/**
	 * Colocar / Adicionar , novo Chave&Valor
	 * @param array &$array Array base
	 * @param type $keys Chave&Valor a ser adicionado /ou apenas chave
	 * @param type|bool $addInExists FALSE Nao add em chave existente
	 * @param type|bool $valueMerge Mesclar valores 
	 * @param type|string $value 
	 * @return bool|Array FALSE caso exista chave
	 */
	public static function put(array &$array, $keys, $addInExists=false, $valueMerge=true, $value='')
	{
		$newKeys = [];
		foreach ((array)$keys as $k => $v) {
			if (is_numeric($k)) $newKeys[] = $v;
			else $newKeys[] = $k;
		}
		if (!$addInExists && self::exists($array, $newKeys)) return false;
		return self::insert($array, $keys, $valueMerge, true, $value);
	}


	/**
	 * Construtor de insert(), exists()
	 * @param array &$array 
	 * @param array $keys 
	 * @param type|bool $valueMerge 
	 * @param type|bool $setExists 
	 * @param type|null $value 
	 * @return array
	 */
	private static function __set__( array &$array, array $keys, $valueMerge=false, $setExists=true, $value=null)
	{

		$count = count($keys)-1;

		for ($i=0; $i < $count; $i++) { 
			$index = $keys[$i];

			if ($index === '[+]') {
				$keysNew = array_slice($keys, $i+1); // pula a o proximo valor/chave
				// var_dump($array);
				foreach ($array as $_k => $_v) {
					if (is_array($_v)) {
						self::__set__($array[$_k], $keysNew, $valueMerge, $setExists, $value);	
					}
				}
				return $array;
			}
			//caso não exista ou não seja uma array cria um vazio para dar continuidade
			//nao inserir em elemento existente
			if (!$setExists && isset($array[$index])) return false;
			elseif (!isset($array[$index]) ||  !is_array($array[$index])) {

				$array[$index] = [];
				
			}

			$array = &$array[$index];

		}


		// caso for setado e nao for array
		if (isset($array[$keys[$i]]) && !is_array($array[$keys[$i]]) && $valueMerge && $setExists) {

			$data = $array[$keys[$i]];
			$array[$keys[$i]] = [];
			$array[$keys[$i]][] = $data;
			$array[$keys[$i]][] = $value;

		} elseif (isset($array[$keys[$i]]) && $valueMerge && $setExists) {

			$array[$keys[$i]][] = $value;

		} else {

			$array[$keys[$i]] = $value;
		}
		
		return $array;

	}

	/**
	 * Construtor de change() ou remove()
	 * @param array &$array 
	 * @param array $keys 
	 * @param type $value 
	 * @param string $method Metodos Aceitos Change|Remove
	 * @return array|null
	 */
	private static function __change__(array &$array, array $keys, $value, $method='change', $compare=false)
	{

		$count = count($keys)-1;

		for ($i=0; $i < $count; $i++) {
			$index = $keys[$i];

			if ($index === '[+]') {
				$keysNew = array_slice($keys, $i+1); // pula a o proximo valor/chave
				// var_dump($array);
				foreach ($array as $_k => $_v) {

					if (is_array($_v)) self::__change__($array[$_k], $keysNew, $value, $method, $compare);	
				}

				return $array;

			}

			if(!is_array($array[$index]) || !isset($array[$index])) return null;
			$array = &$array[$index];
			
		}

		if ('remove' === $method) {
			// var_dump(strtolower($value) == strtolower($array[$keys[$i]]));//debug
			if (is_null($value)  || !is_array($array[$keys[$i]]) && (string)$value === (string)$array[$keys[$i]] || in_array($value, $array[$keys[$i]])) {
				unset($array[$keys[$i]]); 
			}
		} else { //change

			if ($compare) {
				if (!is_array($value)) return null;
				if (is_array($array[$keys[$i]]) && $keyFound = array_keys($array[$keys[$i]], key($value))) {
					foreach ($keyFound as $k) {
						$array[$keys[$i]][$k] = current($value);
					}
				}
			} else {
				$array[$keys[$i]] = $value;
			}

		}
	
		return $array;
	}

	/**
	 * construtor de get(), auxiliar para exists()
	 * @param array $array 
	 * @param mixed $key 
	 * @param bool $recursive
	 * @param bool $methodExists TRUE ativa o  metodo exists, caso nao exista mata o loop
	 * @return type
	 */
	private static function __get__(array $array, $key, $recursive=false, $methodExists=false, $valueCompare=null)
	{


		foreach ($key as $index => $k) {
			// array_shift($key);

			if ($k === '[+]') {
				$pos = array_slice($key, $index+1);
				$result = [];

				foreach ($array as $_v) {
					if(is_array($_v)) {
						$data = self::__get__($_v, $pos, true, $methodExists, $valueCompare);

						if ($methodExists && is_null($data)) return null;

						$result[] = (is_array($data) && isset($data[0])) ? $data[0] : $data;
					}

				}
				return array_filter($result);
			}

			if(!is_array($array) || !isset($array[$k])) return null;
			$array  =  $array[$k];
		}

		// var_dump($valueCompare, (array)$array, in_array($valueCompare, (array)$array));
		return $valueCompare ? in_array($valueCompare, (array)$array) : $array;

	}

 }//END class