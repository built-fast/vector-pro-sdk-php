<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Sites\WafApi;

describe('WafApi', function () {
    describe('allowed referrers', function () {
        it('lists allowed referrers', function () {
            $http = createHttpClient(['data' => []], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('GET');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/allowed-referrers');
            });

            $api = new WafApi($http);
            $api->listAllowedReferrers('site-123');
        });

        it('adds allowed referrer', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('POST');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/allowed-referrers');
                $body = json_decode($request->getBody()->getContents(), true);
                expect($body['hostname'])->toBe('trusted.com');
            });

            $api = new WafApi($http);
            $api->addAllowedReferrer('site-123', ['hostname' => 'trusted.com']);
        });

        it('removes allowed referrer', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('DELETE');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/allowed-referrers/trusted.com');
            });

            $api = new WafApi($http);
            $api->removeAllowedReferrer('site-123', 'trusted.com');
        });
    });

    describe('blocked referrers', function () {
        it('lists blocked referrers', function () {
            $http = createHttpClient(['data' => []], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('GET');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/blocked-referrers');
            });

            $api = new WafApi($http);
            $api->listBlockedReferrers('site-123');
        });

        it('adds blocked referrer', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('POST');
                $body = json_decode($request->getBody()->getContents(), true);
                expect($body['hostname'])->toBe('spam.com');
            });

            $api = new WafApi($http);
            $api->addBlockedReferrer('site-123', ['hostname' => 'spam.com']);
        });

        it('removes blocked referrer', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('DELETE');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/blocked-referrers/spam.com');
            });

            $api = new WafApi($http);
            $api->removeBlockedReferrer('site-123', 'spam.com');
        });
    });

    describe('blocked IPs', function () {
        it('lists blocked IPs', function () {
            $http = createHttpClient(['data' => []], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('GET');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/blocked-ips');
            });

            $api = new WafApi($http);
            $api->listBlockedIPs('site-123');
        });

        it('adds blocked IP', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('POST');
                $body = json_decode($request->getBody()->getContents(), true);
                expect($body['ip'])->toBe('192.168.1.1');
            });

            $api = new WafApi($http);
            $api->addBlockedIP('site-123', ['ip' => '192.168.1.1']);
        });

        it('removes blocked IP', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('DELETE');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/blocked-ips/192.168.1.1');
            });

            $api = new WafApi($http);
            $api->removeBlockedIP('site-123', '192.168.1.1');
        });
    });

    describe('rate limits', function () {
        it('lists rate limits', function () {
            $http = createHttpClient(['data' => []], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('GET');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/rate-limits');
            });

            $api = new WafApi($http);
            $api->listRateLimits('site-123');
        });

        it('creates rate limit', function () {
            $http = createHttpClient(['id' => 'rule-123'], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('POST');
                $body = json_decode($request->getBody()->getContents(), true);
                expect($body['path'])->toBe('/wp-login.php');
                expect($body['requests_per_second'])->toBe(5);
            });

            $api = new WafApi($http);
            $api->createRateLimit('site-123', ['path' => '/wp-login.php', 'requests_per_second' => 5]);
        });

        it('gets rate limit', function () {
            $http = createHttpClient(['id' => 'rule-123'], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('GET');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/rate-limits/rule-123');
            });

            $api = new WafApi($http);
            $api->getRateLimit('site-123', 'rule-123');
        });

        it('updates rate limit', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('PUT');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/rate-limits/rule-123');
                $body = json_decode($request->getBody()->getContents(), true);
                expect($body['requests_per_second'])->toBe(10);
            });

            $api = new WafApi($http);
            $api->updateRateLimit('site-123', 'rule-123', ['requests_per_second' => 10]);
        });

        it('deletes rate limit', function () {
            $http = createHttpClient([], function (RequestInterface $request) {
                expect($request->getMethod())->toBe('DELETE');
                expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/waf/rate-limits/rule-123');
            });

            $api = new WafApi($http);
            $api->deleteRateLimit('site-123', 'rule-123');
        });
    });
});
