<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="templates/bootstrap.min.css" rel="stylesheet">
    <title>Survey Dashboard</title>
    <style>
        body {
            padding-top: 20px;
            padding-left: 10px;
        }

        .panel {
            max-width: 960px;
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
                <div class="row">
                    <div class="col-xs-6">
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
                    <div class="col-xs-6">
                        <p><strong>Nutzerdaten:</strong></p>
                        <table class="table table-condensed table-bordered table-striped common-data">
                            <tr>
                                <td>Gesammelte E-Mail Adressen</td>
                                <td><strong><?php echo $this->data['user']['numberOfEmails'] ?></strong></td>
                            </tr>
                            <tr>
                                <td>Bereit zu weiteren Teilnahmen</td>
                                <td><strong><?php echo $this->data['user']['participateInOther'] ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Letzter Kommentar:</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $this->data['user']['lastComment'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
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
                                <div class="col-xs-4"><strong>A</strong></div>
                                <div class="col-xs-4"><strong>B</strong></div>
                                <div class="col-xs-4"><strong>C</strong></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><strong>A</strong></div>
                                <div class="col-xs-4"><strong>B</strong></div>
                                <div class="col-xs-4"><strong>C</strong></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><strong>A</strong></div>
                                <div class="col-xs-4"><strong>B</strong></div>
                                <div class="col-xs-4"><strong>C</strong></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><strong>A</strong></div>
                                <div class="col-xs-4"><strong>B</strong></div>
                                <div class="col-xs-4"><strong>C</strong></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Anzahl Time-Outs</td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['A']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['B']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['C']['timeouts']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['A']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['B']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['C']['timeouts']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['A']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['B']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['C']['timeouts']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['A']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['B']['timeouts']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['C']['timeouts']) ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Benötigte Entscheidungszeit</td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['A']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['B']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['C']['decision_time']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['A']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['B']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['C']['decision_time']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['A']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['B']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['C']['decision_time']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['A']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['B']['decision_time']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['C']['decision_time']) ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Gewählte Option</td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['A']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['B']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['C']['chosen_option']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['A']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['B']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['C']['chosen_option']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['A']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['B']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['C']['chosen_option']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['A']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['B']['chosen_option']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['C']['chosen_option']) ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Zufriedenheit</td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['A']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['B']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['C']['satisfaction']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['A']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['B']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['C']['satisfaction']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['A']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['B']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['C']['satisfaction']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['A']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['B']['satisfaction']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['C']['satisfaction']) ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Stress</td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['A']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['B']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['lex']['C']['stress']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['A']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['B']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eba']['C']['stress']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['A']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['B']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['eqw']['C']['stress']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row text-center">
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['A']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['B']['stress']) ?></div>
                                <div class="col-xs-4"><?php echo round($this->data['strategy']['wadd']['C']['stress']) ?></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>


