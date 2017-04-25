	<?php

require 'autoload.php';

use darkziul\Helpers\dotNotationArrayAccess as Arr;
// use darkziul\Helpers\accessArrayElementUseEval as accessArrayElementUseEval;
use darkziul\flatDB;

// $accessAE = new accessArrayElement();
$flatdb = new flatDB('_dataDB/');

// var_dump( $flatdb->dbExists('example') );
// var_dump( $flatdb->dbCreate('example') );
// var_dump( $flatdb->db('example') );
// var_dump($flatdb->db('example', true));// //cria database caso nao exista
// var_dump( $flatdb->dbDelete('example') );

// var_dump($flatdb->db('example')->tableShow());
// var_dump($flatdb->db('example')->tableShow(true)); //json

// var_dump($flatdb->db('example')->tableCreate('default'));//create
// var_dump($flatdb->db('example')->table('default'));//instance
// var_dump($flatdb->db('example')->table('default', true));//Caso nao exista table será criado
// var_dump($flatdb->db('example')->tableExists('default'));//exists
// var_dump($flatdb->db('example')->tableDelete('default'), $flatdb->db('example')->tableExists('default'));//delete and check

// $whoArr = [' PARENT ', 'Self', 'OthEr', ' ChilD    '];
// for ($i=0; $i < 2000 ; $i++) {
// 	$arrInsert = [
// 		'who'=> $whoArr[mt_rand(0, count($whoArr)-1)],
// 		'uniqid'=> uniqid(rand(),true),
// 		' NUMBER '=>rand(0,90),
// 		'GrouP.a     '=> substr(uniqid(rand(),true), -10),
// 		'group.b'=> substr(uniqid(rand(),true), -10),
// 		'group.c'=> substr(uniqid(rand(),true), -10),
// 		'unid' => 15,
// 		'collection.item.use' => ['TEST' => [51, 2, 5, ' GnulId' => 999]]
// 	]; 
// 	$flatdb->db('example')->table('default')->insert($arrInsert)->execute();
// }

// var_dump($flatdb->db('example')->table('default')->insert([' Test ' => null, false, '', 5.2100])->execute());//create



// var_dump($flatdb->db('example')->table('default')->put(['collection.item.yers' => 15])->execute());//add (chave&valor) caso nao exista a chave
// var_dump($flatdb->db('example')->table('default')->put(['collection.item.yers' => 15], true)->execute());//add e mescla valor (caso ja exista o valor)
// var_dump($flatdb->db('example')->table('default')->put(['collection.users.password'=>02115])->where(['who'=>'child'])->execute());//add item



//CHANGE ======
var_dump($flatdb->db('example')->table('default')->update(['who'=>'child'])->where(['number'=>10])->execute());



// SELECT =====
// var_dump($flatdb->db('example')->table('default')->select()->where(['id' => 15])->execute());//selecionar
// var_dump($flatdb->db('example')->table('default')->select('group.a')->execute());//selecionar apenas who (todos)
// var_dump($flatdb->db('example')->table('default')->select(['group.a','unid'])->execute());//selecionar apenas 'group.a' e 'unid'
// var_dump($flatdb->db('example')->table('default')->select(['group.a','unid', 'who'])->order('asc', 'who')->execute());//selecionar apenas 'group.a', 'unid' e 'who'. ordernar por 'who' em desc
// var_dump($flatdb->db('example')->table('default')->select(['group.a','unid', 'who'])->order('asc', 'who')->offset(5)->limit(10)->execute());//selecionar apenas 'group.a', 'unid' e 'who'. ordernar por 'who' em desc. pegar uma parte (offset & limit)



// var_dump($flatdb->db('example')->table('default')->delete(15)->execute());//delete ids
// var_dump($flatdb->db('example')->table('default')->delete([10,8])->execute());//delete ids
// var_dump($flatdb->db('example')->table('default')->delete()->where(['who'=>'other', 'number'=>50])->execute());//delete com condicao


// var_dump($flatdb->db('example')->table('default')->length());//total de arquivos salvos
// var_dump($flatdb->db('example')->table('default')->meta());//show metadata
// var_dump($flatdb->db('example')->table('default')->all()); // retorna todos os itens

// $arr45 = [
// 	5 => [
// 		'category' => [
// 			'tag' => 'kl'
// 		],
// 		'name' => 'galbi',
// 		'id' =>5
// 	],
// 	15 => [
// 		'category' => [
// 			'tag' => 'al'
// 		],
// 		'name' => 'sa',
// 		'id' =>15
// 	],
// 	4 => [
// 		'category' => [
// 			'tag' => 'bfc'
// 		],
// 		'name' => 'ab',
// 		'id' =>4
// 	],
// 	1 => [
// 		'category' => [
// 			'tag' => 'st'
// 		],
// 		'name' => '3h',
// 		'id' =>1
// 	],
// ];
// var_dump($flatdb->db('example')->table('default')->parserAndOrder($arr45, ['by'=>'desc', 'key'=>'category.tag']));//test analizar e ordernar

// $arr = [
// 	'main' => [
// 		[
// 			'category' => [
// 					'code' => microtime(true),
// 					'name' => 'Lima',
// 					'tag' => ['ok2','ok5','ok784', 'ok5']
// 				],
// 			'city' => 'rio de janeiro'
// 		],
// 		[
// 			'category' => [
// 					'code' => 'm__' . microtime(true),
// 					'name' => 'Pedro',
// 					'tag' => ['not2784','not7845','not54', 'sub'=>'true']
// 				],
// 			'city' => 'Sao Paulo'
// 		],
// 		'name_sub' => 'falsiane',
// 		'Hola World'
// ]
// ];

// $arrDotNotation = [
// 	'main.person.name' => 'Pedro',
// 	'main.person.city' => 'Kpa'
// ];


// begin REMOVE accessArray ====
// ==========================
// var_dump(Arr::remove($arr, 'main.[+].category'), $arr); //remover chaves
// var_dump(Arr::remove($arr, ['main.[+].category.tag'=> 'ok5', 'main.[+].city']), $arr); //remover chave, se existir o valor /ou for igual
// ==========================
//end REMOVE accessArray =======


// begin GET accessArray ====
// ==========================
// var_dump(Arr::get($arr, ['main.[+].category', 'main.[+].[+].code'], true)); //get com o nome da KEY
// var_dump(Arr::get($arr, ['main.[+].category', 'main.[+].[+].code'])); //get grupo
// var_dump(Arr::get($arr, 'main.[+].category')); //get elemet
// var_dump(Arr::get($arr)); //get all
// ==========================
//end GET accessArray =======


// begin SET accessArray ====
// ==========================
// var_dump(Arr::insert($arr, ['main.[+].category.code.test.RS'=>'coconut'])); //inserir. Insere caso nao exista a chave. Neste exemplo nao sera add nada, pois chave existe
// var_dump(Arr::insert($arr, ['TreeMain.nameShow'=>'coconut'])); //inserir. Insere caso nao exista a chave. Neste exemplo sera inserido 
// var_dump(Arr::put($arr, ['main.[+].category.code'=>'coconut'])); //coloca. caso o valor ou chave ja existir sera subscrito
// var_dump(Arr::put($arr, ['main.[+].category.code'=>'coconut'], true)); //coloca. caso o valor ou chave ja existir sera mesclado com o existente, caso o valor for string, sera convertido em array
// var_dump(Arr::create($arrDotNotation)); //inserir. Insere caso nao exista a chave. Neste exemplo sera inserido 
// ==========================
//end SET accessArray =======

// begin EXIST accessArray ====
// ============================
// var_dump(Arr::exists($arr, 'main.[+].category')); //saber se existe a chave
// var_dump(Arr::exists($arr, 'main.2.category')); //saber se existe a chave   outset::FALSE
// var_dump(Arr::exists($arr, 'main.0.category.tag','ok5')); // saber se existe e seu valor [checa se o valor procurado eh string|Array]
// var_dump(Arr::exists($arr, ['main.1.category.code', 'main.0.category.name'])); //saber se existe o grupo de chaves. No loop caso a chave atual nao existir, retorna false e para o loop
// var_dump(Arr::exists($arr, ['main.0.category.name'=>'lima'])); //saber se existe o grupo de chaves com valores. No loop caso a chave atual 
// var_dump(Arr::exists($arr, ['main.0.category.name'=>'lima', 'main.name_sub'])); //saber se existe o grupo de chaves com valores e apenas chaves.  Consulta mista . No loop caso a chave atual 
// ============================
// end EXIST accessArray ======


// begin CHANGE accessArray ====
// ============================
// var_dump(Arr::change($arr, 'main.[+].category.tag', 'Coconut')); // substituir o  valor, se o valor do seletor for array sera subsescrito pelo novo valor (mixed)
// var_dump(Arr::change($arr, ['main.[+].category.tag'=>'coconut'])); //substituir o valor [metodo (array)chave & valor] 

// var_dump(Arr::change($arr, ['main.[+].category.tag' => ['ok5' => 'Coconut']], '', true)); // substituir o  valor caso exista o valor mencionado selector[current value => new value]
// var_dump(Arr::changeStrict($arr, ['main.[+].category.tag' => ['ok5' => 'Coconut']])); // alternativo de change() [comparar e atualizar]
// ============================
// end CHANGE accessArray ======


// $arr = [
// 	'one' => [
// 		'subone' => ['ok', 'not'],
// 		'list'
// 	],
// 	'two' => 154,
// 	'three' => 'ok'
// ];




// $limit = 10000;
// $begin = microtime(true);
// for ($i=0; $i < $limit; $i++) { 
// 	array_map_recursive(['strtoupper', 'trim'], $arr, true);
// }
// $end = microtime(true);
// var_dump($end - $begin);
// var_dump(array_map_recursive(['strtoupper', 'trim'], $arr));
