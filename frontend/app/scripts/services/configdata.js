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

      //var baseApiUrl = 'http://api.stephan-kopietz.dev';
      var baseApiUrl = 'https://mouselab-promo-thoomi.c9users.io:8080/api';

      var numberOfRounds = 3;
      var experimentConditions = ['C1', 'C2'];
      
      var possibleGroups = ['G1', 'G2', 'G3'];
      var possibleTasks  = ['A',  'B',  'C'];

      var availableTime = {};
      availableTime[possibleTasks[0]] = 171520;
      availableTime[possibleTasks[1]] = 235520;
      availableTime[possibleTasks[2]] = 299520;
      
      var taskOrder = {};
      taskOrder[possibleGroups[0]] = [possibleTasks[0], possibleTasks[1], possibleTasks[2]];
      taskOrder[possibleGroups[1]] = [possibleTasks[1], possibleTasks[2], possibleTasks[0]];
      taskOrder[possibleGroups[2]] = [possibleTasks[2], possibleTasks[0], possibleTasks[1]];
      
      
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
          validity: 0.78,
          cost: 2160,
          weight: 0.27
        },
        {
          validity: 0.61,
          cost: 1680,
          weight: 0.21
        },
        {
          validity: 0.68,
          cost: 1840,
          weight: 0.23
        },
        {
          validity: 0.84,
          cost: 2320,
          weight: 0.29
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
          for (var indexOfOption = 0; indexOfOption < 8; indexOfOption++)
          {
            var indexOfTrial = indexOfComparison * 8 + indexOfOption;
          
            trials[indexOfTrial] = {
              id      : indexOfTrial,
              pairId  : pairComparisons[indexOfComparison].id,
              pattern : pairComparisons[indexOfComparison].pattern.slice()
            };
            
            if (indexOfOption < 4)
            {
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
          return possibleGroups[randomizer.numberBetween(0, 2)];
        },
        
        getRandomCondition : function () {
          return experimentConditions[randomizer.numberBetween(0,1)];
        },
        
        getTask : function(group, round) {
          if (experimentLocation === 'T') { return taskOrder['G1'][round]; }
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
        
        getPairComparison(index) {
          return pairComparisons[index];
        }
      };
  });
