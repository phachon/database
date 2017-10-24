# docs

# used

## config
```
$config = array(
    'type' => 'mysqli',             //database driver type mysqli pr pdo
        'connection' => array (
        'database' => 'test',       //database name
        'hostname' => '127.0.0.1',  //hostname
        'username' => 'root',       //username
        'password' => '123456',     //password
        'socket'   => '',           //socket path
        'port'     => 3306,         //port
        'ssl'      => NULL,         //ssl config, default NULL
    ),
    'charset' => 'utf8',            //charset name
);

```

## mysqli
- select
```
$db = \Database\Database::instance('default', $config);
$db->query("select * from test_account");
var_dump($db->as_array());
var_dump($db->as_object("TestModel"));
```
- update
```

```
- delete
```

```
- insert
```

```
## pdo
- select
- update
- delete
- insert