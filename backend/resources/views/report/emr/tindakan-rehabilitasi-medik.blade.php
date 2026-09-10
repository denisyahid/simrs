@extends('template.head-emr')
@section('title', 'formulir skrining igd')
@section('about', 'Formulir Skrining IGD')

@section('content')

<table width="100%" cellspacing="0">
       
    <tr>
        <td width="100%">
            <table width="100%" border="1" bordercolor="#000000" class="garis6">
                      
                <tr bgcolor="#f08080">
                        <th>No</th>
                        <th>Tanggal dan Jam</th>
                        <th>Tindakan Rehabilitasi Medik</th>
                        <th>Nama Petugas</th>           
                </tr>
                <tr bgcolor="">
                    <td align="left">1</td>
                    <td align="left">
                    @{{ item.obj[3101762] }}
                        <!-- {!!   $res['d'][0]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101763] }}
                        <!-- {!!   $res['d'][2]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101764] }}
                        <!-- {!! $res['d'][4]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">2</td>
                    <td align="left">
                    @{{ item.obj[3101765] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101766] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101767] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">3</td>
                    <td align="left">
                    @{{ item.obj[3101768] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101769] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101770] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">4</td>
                    <td align="left">
                    @{{ item.obj[3101771] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101772] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101773] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">5</td>
                    <td align="left">
                    @{{ item.obj[3101774] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101775] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101776] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">6</td>
                    <td align="left">
                    @{{ item.obj[3101777] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101778] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101779] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">7</td>
                    <td align="left">
                    @{{ item.obj[3101780] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101781] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101782] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">8</td>
                    <td align="left">
                    @{{ item.obj[3101783] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101784] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101785] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">9</td>
                    <td align="left">
                    @{{ item.obj[3101786] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101787] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101788] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <tr bgcolor="">
                    <td align="left">10</td>
                    <td align="left">
                    @{{ item.obj[3101789] }}
                        <!-- {!!   $res['d'][6]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101790] }}
                        <!-- {!!   $res['d'][8]->value !!} -->
                    </td>
                    <td align="left">
                    @{{ item.obj[3101791] }}
                        <!-- {!!   $res['d'][10]->value !!} -->
                    </td>
                </tr>
                <!-- @foreach ($res['d'] as $key => $dd)
                    <tr bgcolor="">
                    <td align="left">
                        {!!  $key + 1  !!}
                    </td>
                        <td align="left">
                        @if( $dd->type == 'datetime')
                             {!!  $dd->value !!}
                        @endif
                        </td>
                        <td align="left">
                        @if( $dd->type == 'textarea')
                             {!!  $dd->value !!}
                        @endif
                        </td>
                        <td align="left">
                        @if( $dd->type == 'combobox')
                             {!!  $dd->value !!}
                        @endif
                        </td>
              
                    </tr>
                    @endforeach -->
            </table>                          
        </td>
    </tr>
   
 
   
    <tr style="text-align: right">
                  
         <td width="100%">
            <font style="font-size: 9pt;font-weight: bold;" color="#000000" >Tanggal dan jam</font><font style="font-size: 9pt;" color="#000000"  id="id_3150373">
            </font>
            <p>  @{{ item.obj[3105792] }}</p>
        </td>   
    </tr>
    <tr style="text-align: right">
                  
        <td width="100%">
            <font style="font-size: 9pt;font-weight: bold;" color="#000000" >Petugas / dokter</font> <font style="font-size: 9pt;" color="#000000" id="id_3150374">
            </font>
            <p> @{{ item.obj[3105793] }}</p>
        </td>            
    </tr>

</table>

@endsection
