<?php
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'].'/class/autoload.php';



function getDisks(){
    $disks = explode(',', preg_replace('/[^0-9a-z\/]+/', ',',trim(shell_exec('df | grep \'/dev/sd\' | awk \'{print $1}\''))));

    $result = [];


    foreach($disks as $disk){
        if(strlen($disk) >= 3)
        {
            $dev = 'df '.$disk.' | grep "'.$disk.'" | awk \'{ print $1}\'';
            $used = 'df '.$disk.' | grep "'.$disk.'" | awk \'{ print $3}\'';
            $available = 'df '.$disk.' | grep "'.$disk.'" | awk \'{ print $4}\'';
            $point = 'df '.$disk.' | grep "'.$disk.'" | awk \'{ print $6}\'';
            $usage = 'df '.$disk.' | grep "'.$disk.'" | awk \'{ print $5}\'';

            $result[] = [
                'dev' => trim($disk),
                'used' => 1028 * trim(shell_exec($used)),
                'available' => 1024 * trim(shell_exec($available)),
                'point' => trim(shell_exec($point)),
                'usage' => str_replace('%', '', trim(shell_exec($usage)))
           ];
        }
    }

    return $result;
}


$commands = [
  'cpu' => [
    'architeture' => 'cat /etc/cpu | grep \'width:\' | awk \'{$1="";print $0}\'',
    'cores' => 'cat /etc/cpu | grep \'physical id:\' | awk \'{$1=$2="";print $0}\'',
    'model' => 'cat /etc/cpu | grep \'product:\' | awk \'{$1="";print $0}\'',
    'vendor' => 'cat /etc/cpu | grep \'vendor:\' | awk \'{$1="";print $0}\'',
    'cache' => 'lscpu | grep \'MiB\' | awk \'{ $1=$2=""; print $3 $4}\'',
    'currentFreq' => 'sync;lscpu | grep \'CPU MHz:\' | awk \'{print $NF}\'MHz',
    'maxFreq' => 'cat /etc/cpu | grep \'capacity:\' | awk \'{print $NF}\''
   ],
  'battery' => [
    'available' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "WARNING" | awk \'{if($2 == "UPower-WARNING"){ print "false"}else{ print "true" }\'',
    'status' => 'upower -i $(upower -e | grep \'BAT\') | grep -E  "state:" | awk \'{ print $NF}\'',
    'usage' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "percentage:" | awk \'{ print $NF}\'',
    'capacity' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "capacity:" | awk \'{ print $NF}\'',
    'energy' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "energy:" | awk \'{$1=""; print $0}\'',
    'energy-full' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "energy-full:" | awk \'{$1=""; print $0}\'',
    'energy-rate' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "energy-rate:" | awk \'{$1=""; print $0}\'',
    'voltage' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "voltage:" | awk \'{$1=""; print $0}\'',
    'technology' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "technology:" | awk \'{$1=""; print $0}\'',
    'icon-name' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "icon-name:" | awk \'{$1=""; print $0}\'',
    'time-left' => 'upower -i $(upower -e | grep \'BAT\') | grep -E "time to empty:" | awk \'{$1=$2=$3=""; print $0}\''
  ],
  'ram' => [
    'total' => 'free | grep \'Mem\' | awk \'{print $2}\'',
    'used' => 'free | grep \'Mem\' | awk \'{print $3}\'',
    'free' => 'free | grep \'Mem\' | awk \'{print $4}\'',
    'shared' => 'free | grep \'Mem\' | awk \'{print $5}\'',
    'available' => 'free | grep \'Mem\' | awk \'{print $7}\''
    ],
    'disks' => [
        'devs' => 'df | grep \'/dev/sd\' | awk \'{print $1}\''
    ],
     'gpu' => [
       'model' => 'lspci | grep \' VGA \' | awk \'{$1=$2=$3=$4=""; print $0}\''
     ],
    'os' => [
        'distro' => 'cat /etc/disto.lib | grep \'Distributor ID:\' | awk \'{ print $NF}\'',
        'description' => 'cat /etc/disto.lib | grep \'Description:\' | awk \'{$1=""; print $0}\'',
        'version' => 'cat /etc/disto.lib | grep \'Release:\' | awk \'{ print $NF}\'',
        'codename' => 'cat /etc/disto.lib | grep \'Codename:\' | awk \'{ print $NF}\''
     ]
  ];


  $data = [
  'os' =>   [
        'distro' => trim(shell_exec($commands['os']['distro'])),
        'description' => trim(shell_exec($commands['os']['description'])),
        'version' => trim(shell_exec($commands['os']['version'])),
        'codename' => trim(shell_exec($commands['os']['codename']))
     ],
   'cpu' => [
      'architeture' => trim(shell_exec($commands['cpu']['architeture'])),
      'cores' => trim(shell_exec($commands['cpu']['cores'])),
      'model' => trim(shell_exec($commands['cpu']['model'])),
      'vendor' => trim(shell_exec($commands['cpu']['vendor'])),
      'cache' => trim(shell_exec($commands['cpu']['cache'])),
      'currentFreq' => floor(trim(shell_exec($commands['cpu']['currentFreq'])) + 100),
      'maxFreq' => trim(shell_exec($commands['cpu']['maxFreq'])),
      'usage' => floor(floor(trim(shell_exec($commands['cpu']['currentFreq'])) + 100) * 100 / str_replace('MHz', '', trim(shell_exec($commands['cpu']['maxFreq']))))
     ],
     'ram' => [
        'total' => 1024 * trim(shell_exec($commands['ram']['total'])) + trim(shell_exec($commands['ram']['shared'])),
        'used' => 1024 * trim(shell_exec($commands['ram']['used'])),
        'shared' => 1024 * (trim(shell_exec($commands['ram']['total'])) + trim(shell_exec($commands['ram']['shared'])) - trim(shell_exec($commands['ram']['available']))),
        'free' => 1024 * trim(shell_exec($commands['ram']['free'])),
        'available' => 1024 * trim(shell_exec($commands['ram']['available'])),
        'usage' => floor(trim(shell_exec($commands['ram']['used'])) * 100 / trim(shell_exec($commands['ram']['available'])))
     ],
    'gpu' => [
       'model' => trim(shell_exec($commands['gpu']['model'])),
       'memory' => 1024 * (trim(shell_exec($commands['ram']['total'])) + trim(shell_exec($commands['ram']['shared'])) - trim(shell_exec($commands['ram']['available']))),
     ],
     'disks' => getDisks(),
    'battery' => [
      'available' => strlen(trim(shell_exec($commands['battery']['available']))) > 0 ? 'false' : 'true',
      'status' => trim(shell_exec($commands['battery']['status'])),
      'usage' => str_replace('%', '', trim(shell_exec($commands['battery']['usage']))),
      'capacity' => str_replace('%', '', trim(shell_exec($commands['battery']['capacity']))),
      'energy' => trim(shell_exec($commands['battery']['energy'])),
      'energy_full' => trim(shell_exec($commands['battery']['energy-full'])),
      'energy_rate' => trim(shell_exec($commands['battery']['energy-rate'])),
      'voltage' => trim(shell_exec($commands['battery']['voltage'])),
      'technology' => trim(shell_exec($commands['battery']['technology'])),
      'icon-name' => str_replace("'", '', trim(shell_exec($commands['battery']['icon-name']))),
      'time-left' => strlen(trim(shell_exec($commands['battery']['time-left']))) > 0 ? trim(shell_exec($commands['battery']['time-left'])) : 'Não Disponivel,Usando AC'
    ]
   ];


if(isset($_REQUEST['pre']))
{
    echo "<pre>";
       print_r($data);
    echo "<pre>"; 
}else{
    echo json_encode($data, 64 | 128 );
}
