<!DOCTYPE html>
<head>
    <script src="dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
    <link rel="stylesheet" href="dhtmlxGrid/codebase/dhtmlxgrid.css">
</head>

<body>
    <div id="grid_here" style="width: 600px; height: 400px;"></div>
    <script type="text/javascript" charset="utf-8">
        mygrid = new dhtmlXGridObject('grid_here');
        mygrid.setHeader("Start date,End date,Text");
        mygrid.init();
        mygrid.load("./grid_data");
        var dp = new dataProcessor("./grid_data");
        dp.init(mygrid);
    </script>
</body>