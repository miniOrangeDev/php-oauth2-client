<!DOCTYPE html>
<html>
    <head>
        <title>Test Configuration</title>
    </head>
    <body>
        <div style="font-family:Calibri;padding:0 3%;">
            <style>
                table{border-collapse:collapse;}
                th {background-color: #eee; text-align: center; padding: 8px; border-width:1px; border-style:solid; border-color:#212121;}
                tr:nth-child(odd) {background-color: #f2f2f2;} 
                td{padding:8px;border-width:1px; border-style:solid; border-color:#212121;}
            </style>
            <h2>Test Configuration</h2>
            <table>
                <tbody><tr><th>Attribute Name</th><th>Attribute Value</th></tr>
                    @foreach ($user_info as $key => $user)
                        <tr><td>{{$key}}</td><td>{{$user}}</td></tr>
                    @endforeach
                </tbody></table>
                <div style="padding: 10px;"></div>
                <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;" type="button" value="Done" onclick="self.close();"></div>
    </body>
</html>