'use strict';

/**
 * @ngdoc service
 * @name mouselabApp.dataService
 * @description
 * # dataService
 * Service in the mouselabApp.
 */
angular.module('mouselabApp')
  .service('dataService', function ($http, configData) {
        var participantDatabaseId = 0;
        var participantId         = 0;
        var participantGroup      = '';
        var startTime             = 0;
        var endTime               = 0;

        //var participantAge        = 0;
        //var participantGender     = '';
        //var participantGraduation = 0;

        var currentExperimentRound   = 1;
        var lastExperimentDatabaseId = 0;

        // Helping variables to determine if data is already saved
        // For example if the user hits the back button and answers the questions twice
        var isParticipantSaved    = false;
        var isExperimentSaved     = [false, false, false];
        var isStressQuestionSaved = [false, false, false];
        var isDemographicsSaved   = false;
        var isMaximisingSaved     = false;

        // Define private http request methods
        function saveParticipant(callback) {
            var postData = {
                participantId       : participantId,
                participantGroup    : participantGroup,
                participantLocation : configData.getExperimentLocation()
            };

            $http.post(configData.getBaseUrl() + '/participant/create', postData).
                success(function(data) {
                    participantDatabaseId = data.participantDatabaseId;
                    isParticipantSaved = true;

                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveExperiment(optionRank, timeToDecision, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                condition             : configData.getTask(participantGroup, currentExperimentRound),
                conditionPosition     : currentExperimentRound,
                chosenOptionRank      : optionRank,
                timeToDecision        : timeToDecision
            };

            $http.post(configData.getBaseUrl() + '/experiment/create', postData).
                success(function(data) {
                    lastExperimentDatabaseId = data.experimentDbId;
                    isExperimentSaved[currentExperimentRound-1] = true;

                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveStressQuestions(valueQuestion1, valueQuestion2, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                experimentDatabaseId  : lastExperimentDatabaseId,
                valueQuestion1        : valueQuestion1,
                valueQuestion2        : valueQuestion2,
                questionSum           : parseInt(valueQuestion1) + parseInt(valueQuestion2)
            };

            $http.post(configData.getBaseUrl() + '/experiment/save/stressquestions', postData).
                success(function() {
                    isStressQuestionSaved[currentExperimentRound-1] = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveDemographicData(age, gender, graduation, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                age                   : age,
                gender                : gender,
                graduation            : graduation
            };

            $http.post(configData.getBaseUrl() + '/participant/save/demographics', postData).
                success(function() {
                    isDemographicsSaved = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveMaximisingAnswers(answerValues, sumAnswers, callback) {
            var postData = {
                participantDatabaseId : participantDatabaseId,
                answerValues          : answerValues,
                sumAnswers            : sumAnswers,
                totalTime             : parseInt(endTime) - parseInt(startTime)
            };

            $http.post(configData.getBaseUrl() + '/participant/save/maximisinganswers', postData).
                success(function() {
                    isMaximisingSaved = true;
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }

        function saveUserData(email, participateInOther, comments, callback) {
            var postData = {
                email                 : email,
                participateInOther    : participateInOther,
                comments              : comments,
                location              : configData.getExperimentLocation()
            };

            $http.post(configData.getBaseUrl() + '/user/create', postData).
                success(function() {
                    callback();
                }).
                error(function() {
                    callback('Error: http request went wrong.');
                });
        }


        return {

            startTime : function() {
                startTime = new Date().getTime();
            },

            endTime : function() {
                endTime = new Date().getTime();
            },

            isLastRound : function () {
                return currentExperimentRound === configData.getMaxRounds();
            },

            getCurrentRound : function () {
              return currentExperimentRound;
            },

            startNextRound : function() {
                if (currentExperimentRound >= 1 && currentExperimentRound <= configData.getMaxRounds())
                {
                    currentExperimentRound +=1;
                    return true;
                }
                else
                {
                    return false;
                }
            },

            getCurrentTask : function () {
              return configData.getTask(participantGroup, currentExperimentRound);
            },

            initializeParticipant : function (id, callback) {
                if (isParticipantSaved)
                {
                    callback();
                    return;
                }

                participantId    = id;
                participantGroup = configData.getRandomGroup();

                if (typeof callback === 'function')
                {
                    saveParticipant(callback);
                }
            },

            saveExperiment : function(optionRank, timeToDecision, callback) {
                if (isExperimentSaved[currentExperimentRound-1])
                {
                    callback();
                    return;
                }

              saveExperiment(optionRank, timeToDecision, callback);
            },

            saveStressQuestions : function (answer1, answer2, callback) {
                if (isStressQuestionSaved[currentExperimentRound-1])
                {
                    callback();
                    return;
                }

                saveStressQuestions(answer1, answer2, callback);
            },

            saveDemographicData : function (age, gender, graduation, callback) {
                if (isDemographicsSaved)
                {
                    callback();
                    return;
                }

                saveDemographicData(age, gender, graduation, callback);
            },

            saveMaximisingAnswers : function (answerValues, sumAnswers, callback) {
                if (isMaximisingSaved)
                {
                    callback();
                    return;
                }

                saveMaximisingAnswers(answerValues, sumAnswers, callback);
            },

            saveUserData : function(email, participateInOther, comments, callback) {
                saveUserData(email, participateInOther, comments, callback);
            },

            everythingIsValid : function () {
                // if the participant id is set and so on
                var dataIsFine = true;

                if (participantDatabaseId === 0 || participantId === 0 || participantGroup === '')
                {
                    dataIsFine = false;
                }

                return dataIsFine;
            },

            clearAllData : function() {
                participantDatabaseId    = 0;
                participantId            = 0;
                participantGroup         = '';
                startTime                = 0;
                endTime                  = 0;
                currentExperimentRound   = 1;
                lastExperimentDatabaseId = 0;
            }
        };
  });
