<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <title>Survey Dashboard</title>
    <style>
        body {
            padding-top: 20px;
            padding-left: 10px;
        }

        .panel {
            max-width: 780px;
        }
        .common-data {
            max-width: 350px;
        }
        .border-bottom-bold {
            border-bottom: 2px solid #aaa;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Dashboard Masterarbeit</strong></div>
        <div class="panel-body">
            <p><strong>Allgemeine Daten:</strong></p>
            <table class="table table-condensed table-bordered table-striped common-data">
                <tr>
                    <td>Anzahl Teilnehmer</td>
                    <td><strong><?php echo $this->data['general']['numberOfParticipants'] ?></strong></td>
                </tr>
                <tr>
                    <td>Anzahl Vorstudie</td>
                    <td><strong><?php echo $this->data['general']['numberOfPreviousParticipants'] ?></strong></td>
                </tr>
                <tr>
                    <td>Anzahl Drop-Outs</td>
                    <td><strong><?php echo $this->data['general']['numberOfDropOuts'] ?></strong></td>
                </tr>
                <tr>
                    <td>Dauer der Studienteilnahme</td>
                    <td><strong><?php echo round($this->data['general']['averageTotalTime']) ?></strong></td>
                </tr>
                <tr>
                    <td>Mittelwert Maximierungstendenz</td>
                    <td><strong><?php echo round($this->data['general']['averageMaximising']) ?></strong></td>
                </tr>
                <tr>
                    <td>Anteil Männer und Frauen</td>
                    <td><strong><?php echo $this->data['general']['genderShare']['male'] . ' / ' . $this->data['general']['genderShare']['female'] ?></strong></td>
                </tr>
                <tr>
                    <td>Durchschnittliches Alter</td>
                    <td><strong><?php echo round($this->data['general']['averageAge']) ?></strong></td>
                </tr>
            </table>
        </div>

        <table class="table table-condensed table-bordered table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">LEX</th>
                    <th class="text-center">EBA</th>
                    <th class="text-center">EQW</th>
                    <th class="text-center">WADD</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-bottom-bold">
                    <td>Anzahl Teilnehmer</td>
                    <td class="text-center"><?php echo round($this->data['strategy']['lex']['numberOfParticipants']) ?></td>
                    <td class="text-center"><?php echo round($this->data['strategy']['eba']['numberOfParticipants']) ?></td>
                    <td class="text-center"><?php echo round($this->data['strategy']['eqw']['numberOfParticipants']) ?></td>
                    <td class="text-center"><?php echo round($this->data['strategy']['wadd']['numberOfParticipants']) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div class="row text-center">
                            <div class="col-xs-4">A</div>
                            <div class="col-xs-4">B</div>
                            <div class="col-xs-4">C</div>
                        </div>
                    </td>
                    <td>
                        <div class="row text-center">
                            <div class="col-xs-4">A</div>
                            <div class="col-xs-4">B</div>
                            <div class="col-xs-4">C</div>
                        </div>
                    </td>
                    <td>
                        <div class="row text-center">
                            <div class="col-xs-4">A</div>
                            <div class="col-xs-4">B</div>
                            <div class="col-xs-4">C</div>
                        </div>
                    </td>
                    <td>
                        <div class="row text-center">
                            <div class="col-xs-4">A</div>
                            <div class="col-xs-4">B</div>
                            <div class="col-xs-4">C</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Anzahl Time-Outs</td>
                    <td>5</td>
                    <td>10</td>
                    <td>4</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>Benötigte Entscheidungszeit</td>
                    <td>5</td>
                    <td>10</td>
                    <td>4</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>Gewählte Option</td>
                    <td>5</td>
                    <td>10</td>
                    <td>4</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>Zufriedenheit</td>
                    <td>5</td>
                    <td>10</td>
                    <td>4</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>Stress</td>
                    <td>5</td>
                    <td>10</td>
                    <td>4</td>
                    <td>8</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>


