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
        .dashboard-btn {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Dashboard Grundlagenstudie</strong>
                <a href="/csv" class="btn btn-info pull-right btn-sm dashboard-btn">Data CSV</a>
                <a href="/user" class="btn btn-info pull-right btn-sm dashboard-btn">User CSV</a>
                <div class="clearfix"></div></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <p><strong>Allgemeine Daten:</strong></p>
                        <table class="table table-condensed table-bordered table-striped ">
                            <tr>
                                <td>Anzahl Teilnehmer</td>
                                <td><strong><?php echo $this->data['general']['numberOfParticipants'] ?></strong></td>
                            </tr>
                            <tr>
                                <td>Anteil Frauen und Männer</td>
                                <td><strong><?php echo $this->data['general']['genderShare']['male'] . ' / ' . $this->data['general']['genderShare']['female'] ?></strong></td>
                            </tr>
                            <tr>
                                <td>Anteil VpH und Auszahlung</td>
                                <td><strong><?php echo $this->data['general']['rewardShare']['reward1'] . ' / ' . $this->data['general']['rewardShare']['reward2'] ?></strong></td>
                            </tr>
                            <tr>
                                <td>Durchschnittliche Dauer</td>
                                <td><strong><?php echo floor(($this->data['general']['averageTotalTime'] / (1000.0 * 60.0)) % 60) . ' min ' . (($this->data['general']['averageTotalTime'] / 1000.0) % 60) . ' sek' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Durchschnittlicher Auszahlungsbetrag</td>
                                <td><strong><?php echo round($this->data['general']['payout']['average'], 2) ?></strong></td>
                            </tr>
                            <tr>
                                <td>Auszahlungen Gesamt</td>
                                <td><strong><?php echo round($this->data['general']['payout']['sum'], 2) ?></strong></td>
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
                <div class="row">
                    <div class="col-xs-12">
                        <p><strong>Letzte Teilnehmer: </strong></p>
                        <table class="table table-condensed table-bordered table-striped">
                            <tr>
                                <td>End Zeitpunkt</td>
                                <td>Pers. Code</td>
                                <td>Gesamtdauer</td>
                                <td>Betrag</td>
                            </tr>
                            <?php foreach($this->data['general']['payoutParticipants'] as $value): ?>
                            <tr>
                                <td><?php echo date('d.m.Y H:i:s', $value['endtime'] / 1000.0); ?></td>
                                <td><?php echo $value['participation_id'] ?></td>
                                <td><?php echo floor(($value['total_time'] / (1000.0 * 60.0)) % 60) . ' min ' . (($value['total_time'] / 1000.0) % 60) . ' sek' ?></td>
                                <td><?php echo min($value['payout'], 5.95) . ' €'?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


