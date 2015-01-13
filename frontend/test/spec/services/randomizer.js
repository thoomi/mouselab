'use strict';

describe('Service: randomizer', function () {

  // load the service's module
  beforeEach(module('mouselabApp'));

  // instantiate service
  var randomizer;
  beforeEach(inject(function (_randomizer_) {
    randomizer = _randomizer_;
  }));

  it('should do something', function () {
    expect(!!randomizer).toBe(true);
  });

});
