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

      var baseApiUrl = 'http://api-002.stephan-kopietz.de';
      //var baseApiUrl = 'https://mouselab-promo-thoomi.c9users.io:8080/api';

      var numberOfRounds = 6;
      var experimentConditions = ['C1', 'C2'];

      var possibleGroups = ['G1', 'G2', 'G3', 'G4', 'G5', 'G6'];
      var possibleTasks  = ['X_A',  'X_B',  'X_C', 'Y_A',  'Y_B',  'Y_C'];

      var availableTime = {};
      availableTime[possibleTasks[0]] = 18060;
      availableTime[possibleTasks[1]] = 81270;
      availableTime[possibleTasks[2]] = 144480;
      //availableTime[possibleTasks[0]] = 17150;
      //availableTime[possibleTasks[1]] = 23552;
      //availableTime[possibleTasks[2]] = 29952;
      availableTime[possibleTasks[3]] = availableTime[possibleTasks[0]];
      availableTime[possibleTasks[4]] = availableTime[possibleTasks[1]];
      availableTime[possibleTasks[5]] = availableTime[possibleTasks[2]];

      var taskOrder = {};
      taskOrder[possibleGroups[0]] = [possibleTasks[0], possibleTasks[1], possibleTasks[2], possibleTasks[3], possibleTasks[4], possibleTasks[5]];
      taskOrder[possibleGroups[1]] = [possibleTasks[1], possibleTasks[2], possibleTasks[0], possibleTasks[4], possibleTasks[5], possibleTasks[3]];
      taskOrder[possibleGroups[2]] = [possibleTasks[2], possibleTasks[0], possibleTasks[1], possibleTasks[5], possibleTasks[3], possibleTasks[4]];
      taskOrder[possibleGroups[3]] = [possibleTasks[3], possibleTasks[4], possibleTasks[5], possibleTasks[0], possibleTasks[1], possibleTasks[2]];
      taskOrder[possibleGroups[4]] = [possibleTasks[4], possibleTasks[5], possibleTasks[3], possibleTasks[1], possibleTasks[2], possibleTasks[0]];
      taskOrder[possibleGroups[5]] = [possibleTasks[5], possibleTasks[3], possibleTasks[4], possibleTasks[2], possibleTasks[0], possibleTasks[1]];


      var maxPossibleTrials = {};
      maxPossibleTrials[possibleTasks[0]] = 8;
      maxPossibleTrials[possibleTasks[1]] = 36;
      maxPossibleTrials[possibleTasks[2]] = 64;
      maxPossibleTrials[possibleTasks[3]] = 8;
      maxPossibleTrials[possibleTasks[4]] = 36;
      maxPossibleTrials[possibleTasks[5]] = 64;

      var maxScore = {}
      maxScore[possibleTasks[0]] = 168;
      maxScore[possibleTasks[1]] = 755;
      maxScore[possibleTasks[2]] = 1341;
      maxScore[possibleTasks[3]] = 168;
      maxScore[possibleTasks[4]] = 755;
      maxScore[possibleTasks[5]] = 1341;

      var cueLabels = [
        'Positiver Aktienverlauf?',
        'Finanzielle Reserven?',
        'Investiert in neue Projekte?',
        'Etabliertes Unternehmen?'
      ];
      randomizer.shuffleArray(cueLabels);


      // validity is given in percentage
      // cost is given in milliseconds
      var cueValues = [
        {
          validity: 0.84,
          cost: 1740,
          weight: 0.29
        },
        {
          validity: 0.78,
          cost: 1600,
          weight: 0.27
        },
        {
          validity: 0.68,
          cost: 1400,
          weight: 0.23
        },
        {
          validity: 0.61,
          cost: 1260,
          weight: 0.21
        },
      ];

      var pairComparisons = [
        {
          id: 1,
          pattern: [1, 1, 1, 1]
        },
        {
          id: 2,
          pattern: [1, 1, 1, 0]
        },
        {
          id: 3,
          pattern: [1, 1, 0, 1]
        },
        {
          id: 4,
          pattern: [1, 0, 1, 1]
        },
        {
          id: 5,
          pattern: [0, 1, 1, 1]
        },
        {
          id: 6,
          pattern: [1, 1, 0, 0]
        },
        {
          id: 7,
          pattern: [1, 0, 1, 0]
        },
        {
          id: 8,
          pattern: [1, 0, 0, 1]
        }
      ];

      function generateTrials() {
        var trials = [];

        for(var indexOfComparison = 0; indexOfComparison < 8; indexOfComparison++)
        {
          for (var indexOfOption = 0; indexOfOption < 2; indexOfOption++)
          {
            var indexOfTrial = indexOfComparison * 2 + indexOfOption;

            trials[indexOfTrial] = {
              id      : indexOfTrial,
              pairId  : pairComparisons[indexOfComparison].id,
              optionId: pairComparisons[indexOfComparison].id,
              pattern : pairComparisons[indexOfComparison].pattern.slice()
            };

            if (indexOfOption == 1)
            {
              trials[indexOfTrial].optionId = 17 - pairComparisons[indexOfComparison].id;

              trials[indexOfTrial].pattern[0] = 1 - trials[indexOfTrial].pattern[0];
              trials[indexOfTrial].pattern[1] = 1 - trials[indexOfTrial].pattern[1];
              trials[indexOfTrial].pattern[2] = 1 - trials[indexOfTrial].pattern[2];
              trials[indexOfTrial].pattern[3] = 1 - trials[indexOfTrial].pattern[3];
            }
          }
        }

        randomizer.shuffleArray(trials);

        return trials;
      }


      return {
        getMaxRounds : function () {
          return numberOfRounds;
        },

        getExperimentLocation : function () {
          return experimentLocation;
        },

        getBaseUrl : function () {
            return baseApiUrl;
        },

        getRandomGroup : function () {
          return possibleGroups[randomizer.numberBetween(0, 5)];
        },

        getRandomCondition : function () {
          return experimentConditions[randomizer.numberBetween(0, 1)];
        },

        getTask : function(group, round) {
          //if (experimentLocation === 'T') { return taskOrder['G6'][round - 1]; }
          return taskOrder[group][round - 1];
        },

        getAvailableTime : function (task) {
          return availableTime[task];
        },

        getCueValue : function(index) {
          return cueValues[index];
        },

        getCueValues : function() {
          return cueValues;
        },

        getCueLabels : function() {
          return cueLabels;
        },

        getTrials : function() {
          return generateTrials();
        },

        getPairComparison : function(index) {
          return pairComparisons[index];
        },

        getMaxPossibleTrials : function(task) {
          return maxPossibleTrials[task];
        },

        getMaxScore : function(task) {
          return maxScore[task];
        },

        isTaskWithoutWaiting : function(task) {
          if (task === 'Y_A' || task === 'Y_B' || task === 'Y_C')
          {
            return true;
          }
          else
          {
            return false;
          }
        }
      };
  });
