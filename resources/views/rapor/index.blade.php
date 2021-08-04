<!doctype html>
<html>
    <head>
        <title>Rapor Asrama Kelas {{$class->level.$class->rombel}} {{$class->sekolah->nama}}</title>
        <style>
            * {
                font-family: Calibri
            }
            
            .header {
                text-align: center;
                padding: 5px
            }
            .header h2,
            .header h3 {
                margin-bottom: 2px;
                
            }
            .header h2,
            .header h4{
                margin-top: 2px
            }


            .student {
                margin-top: 20px
            }
            .student h2 {
                border:  solid 2px black;
                text-align: center;
                margin-bottom: 2px
            }

            .score table {
                width: 100%;
                table-layout: fixed; 
                border-collapse: collapse;
            }
            .score table tbody tr td,
            .score table thead tr th {
                border : solid 2px #000000;  
                padding: 3px
            }

            .chart {
                margin-top: 20px;
            }

            .container {

            }

            .text-center {
                text-align: center
            }

            @media print   {
            /* you can change the selector to whatever you need */
                .no-break-page {
                    break-inside: avoid;
                    page-break-inside: avoid;
                } 
                .page-break { page-break-before: always;  padding-top: 20px; } /* page-break-after works, as well */
                @page {
                    size: auto;   /* auto is the initial value */
                    margin-top: 0;  /* this affects the margin in the printer settings */
                    margin-bottom: 0; 
                
                }
                .font-black {
                    color: rgb(0,0,0);
                }
        }

        .score-label {
            font-size: 0.5em
        }
        .category-label {
            font-size: 0.5em
        }

        </style>
    </head>
    <body>
        @foreach ($items as $item)
        @php
            $categories_data = $item['categories'];
            $categories_count = $item['categories_count'];
        @endphp
        <div class="container">
            <div class="header">
                <h3>THE STUDENT AFFECTIVE DEVELOPMENT REPORT</h3>
                <h2>KAFILA INTERNATIONAL ISLAMIC SCHOOL JAKARTA</h2>
                <h4>Semester/TA: {{$semester == 2 ? 'Genap' : 'Gasal'}}/{{$tahun_ajaran}}</h4>
            </div>
            <div class="student">
                <h2>{{$item['name']}}</h2>
                <p>Kelas: {{$item['class']}}</p>
            </div>
            <div class="score">
                <table>
                    <thead>
                        <tr>
                            <th>Definisi Skor</th>
                            <th>Kompetensi</th>
                            <th colspan="2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories_data as $category_data)
                            <tr>
                                <td>{{$category_data['name']}}</td>
                                <td class="text-center">
                                    {{$category_data['predicate_status']}}
                                </td>
                                <td class="text-center" colspan="2">
                                    {{$category_data['predicate_desc']}}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="2"></td>
                            <td class="text-center">Skor Pelanggaran</td>
                            <td class="text-center">Jenis Pelanggaran</td>
                        </tr>
                        <tr>
                            <td>Nilai rata-rata</td>
                            <td class="text-center">{{$item['average_status']}}</td>
                            <td class="text-center">{{$item['violation_point']}}</td>
                            <td>{{$item['violation_name'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" >
                                <span style="visibility: hidden">Footer</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="chart text-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {{50 * ($categories_count + 1)}} 180" style="width: 80%">
                    
                    @foreach ([0, 20, 40, 60, 80, 100] as $score_indicator)
                        @php
                            $y_point = 30 + 100 - ($loop->index * 20);
                            $x_point = 50 * ($categories_count + 1);
                        @endphp
                        <text class="score-label" x="0" y="{{$y_point}}">
                            {{$score_indicator}}
                        </text>
                        <path id="axis_border" fill="none" stroke-width="1" stroke="#ccc"
                            d="M 15 {{ $y_point }} L {{ $x_point }} {{ $y_point }}"  />
                        
                    @endforeach
                    <path id="axis_border" fill="none" stroke-width="1" stroke="#000" d="M 15 0 L 15 130 L {{50 * ($categories_count + 1)}} 130 " />
                    @php
                        $line_path = '';
                    @endphp
                    @foreach ($categories_data as $category_data)
                        @php
                            $score = $category_data['score'];
                            $y_point = 130 - $score;
                            $x_point = 50 * ($loop->index + 1);
                            $command = $loop->index == 0 ? ' M ':' L ';
                            $line_path .= $command . $x_point.' '.$y_point.' ';
                        @endphp 
                        <circle  stroke-width="2" stroke="rgb(100, 100, 100)" cx="{{$x_point}}" cy="{{$y_point}}" r="1" />
                        <text class="score-label" x="{{$x_point}}" y="{{$y_point - 5}}">
                             {{$score}} 
                        </text>
                        <text class="category-label"  transform="translate({{$x_point - 10}},140) rotate(20)">
                             {{$category_data['name']}} 
                        </text>
                    @endforeach
                    
                    <path class="line_chart" fill="none" sroke-witdh="2" stroke="rgb(100, 100, 100)" d="{{$line_path}}"    />
                    
                </svg>
            </div>
        </div>
        <div class="page-break"></div>

        @endforeach
    </body>
</html>