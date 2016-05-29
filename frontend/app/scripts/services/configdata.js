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
      availableTime[possibleTasks[0]] = 17152;
      availableTime[possibleTasks[1]] = 23552;
      availableTime[possibleTasks[2]] = 29952;
      
      var taskOrder = {};
      taskOrder[possibleGroups[0]] = [possibleTasks[0], possibleTasks[1], possibleTasks[2]];
      taskOrder[possibleGroups[1]] = [possibleTasks[1], possibleTasks[2], possibleTasks[0]];
      taskOrder[possibleGroups[2]] = [possibleTasks[2], possibleTasks[0], possibleTasks[1]];
      
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
          return taskOrder[group][round - 1];
        },
        
        getAvailableTime : function (task) {
          return availableTime[task];
        },
      };
  });
