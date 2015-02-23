'use strict';

/**
 * @ngdoc service
 * @name mouselabApp.configData
 * @description
 * # configData
 * Service in the mouselabApp.
 */
angular.module('mouselabApp')
  .service('configData', function (randomizer) {
      // Location will be different for each url
      var experimentLocation  = 'T';

      //var baseApiUrl = 'http://testlab.dev';
      var baseApiUrl = 'http://apilab.stephan-kopietz.de';

      var numberOfRounds = 3;
      var possibleGroups = ['G1', 'G2', 'G3'];
      var possibleTasks  = ['A',  'B',  'C'];

      // Define the washing powder rating test cases
      var ratingTestCases = [
        'Preis je Waschgang in Cent',
        'Schmutzentfernung',
        'Fleckenentfernung',
        'Vergrauung',
        'Farberhaltung',
        'Faserschutz',
        'Umwelteigenschaften'
      ];

      var washingPowders = {};

      washingPowders[possibleTasks[0]] = [
        { name  : 'Waschmittel 1', testCaseRatings : [0.31, '+', '+', '-', 'o', '++', '+'],   rank : 4},
        { name  : 'Waschmittel 2', testCaseRatings : [0.28, 'o', '+', 'o', '++', '++', '++'], rank : 2},
        { name  : 'Waschmittel 3', testCaseRatings : [0.20, '+', '++', 'o', '+', '+', '++'],  rank : 1},
        { name  : 'Waschmittel 4', testCaseRatings : [0.19, '+', '+', '-', 'o', 'o', '+'],    rank : 3}
      ];

      washingPowders[possibleTasks[1]] = [
        { name  : 'Waschmittel 1', testCaseRatings : [0.21, '+', 'o', '-', '++', '+', '--'], rank : 3},
        { name  : 'Waschmittel 2', testCaseRatings : [0.28, 'o', '++', '-', 'o', 'o', '+'],  rank : 5},
        { name  : 'Waschmittel 3', testCaseRatings : [0.25, '++', '+', '+', 'o', '--', 'o'], rank : 4},
        { name  : 'Waschmittel 4', testCaseRatings : [0.20, '+', '+', '+', '-', '++', '+'],  rank : 2},
        { name  : 'Waschmittel 5', testCaseRatings : [0.17, '++', '+', 'o', '+', '-', '++'], rank : 1},
        { name  : 'Waschmittel 6', testCaseRatings : [0.31, '+', '-', '+', '--', '++', 'o'], rank : 6}
      ];

      washingPowders[possibleTasks[2]] = [
        { name  : 'Waschmittel 1', testCaseRatings : [0.20, '+', '++', 'o', '--', 'o', '+'], rank : 1},
        { name  : 'Waschmittel 2', testCaseRatings : [0.37, '-', 'o', '+', '+', 'o', '++'],  rank : 7},
        { name  : 'Waschmittel 3', testCaseRatings : [0.27, 'o', '+', '+', 'o', '+', '--'],  rank : 5},
        { name  : 'Waschmittel 4', testCaseRatings : [0.28, '+', '-', '++', '--', 'o', '-'], rank : 8},
        { name  : 'Waschmittel 5', testCaseRatings : [0.31, '++', 'o', '-', '++', '+', 'o'], rank : 4},
        { name  : 'Waschmittel 6', testCaseRatings : [0.23, '+', '+', '+', 'o', '--', '+'],  rank : 3},
        { name  : 'Waschmittel 7', testCaseRatings : [0.22, '+', '+', '--', '+', '-', '++'], rank : 2},
        { name  : 'Waschmittel 8', testCaseRatings : [0.33, '++', '-', 'o', '+', '+', 'o'],  rank : 6}
      ];

      // Define the task order (The order how the user will see it)
      var taskOrder = {};
      taskOrder[possibleGroups[0]] = [possibleTasks[0], possibleTasks[1], possibleTasks[2]];
      taskOrder[possibleGroups[1]] = [possibleTasks[1], possibleTasks[2], possibleTasks[0]];
      taskOrder[possibleGroups[2]] = [possibleTasks[2], possibleTasks[0], possibleTasks[1]];


      return {
        getMaxRounds : function () {
          return numberOfRounds;
        },

        getRandomGroup : function () {
          return possibleGroups[randomizer.numberBetween(0, 2)];
        },

        getTask : function(group, round) {
          return taskOrder[group][round - 1];
        },

        getWashingPowders : function (task) {
          return washingPowders[task];
        },

        getExperimentLocation : function () {
          return experimentLocation;
        },

        getRatingTestCases : function () {
          return ratingTestCases;
        },

        getBaseUrl : function () {
            return baseApiUrl;
        }
      };
  });
