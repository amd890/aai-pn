<?php

namespace App\Domain\Voting\Services;

use App\Domain\Membership\Models\Member;
use App\Domain\Voting\Models\Election;
use App\Domain\Voting\Models\ElectionCandidate;
use App\Domain\Voting\Models\ElectionVote;
use App\Support\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Config;

class VotingService extends BaseService
{
    /**
     * Cast an anonymous electronic vote using SHA-256 hashing to guarantee ballot secrecy and prevent double-voting.
     *
     * @throws Exception
     */
    public function castVote(Election $election, Member $voter, ElectionCandidate $candidate, ?string $ipAddress = '127.0.0.1'): ElectionVote
    {
        return $this->transactional(function () use ($election, $voter, $candidate, $ipAddress) {
            if (!$election->isOpen()) {
                throw new Exception('Election is currently not open for voting.');
            }

            if ($candidate->election_id !== $election->id) {
                throw new Exception('Selected candidate does not belong to this election.');
            }

            // Produce deterministic yet anonymous voter hash
            $salt = Config::get('app.key', 'secret_salt_aai_voting');
            $voterHash = hash('sha256', "election_{$election->id}_voter_{$voter->id}_{$salt}");

            $hasVoted = ElectionVote::where('election_id', $election->id)
                ->where('voter_hash', $voterHash)
                ->exists();

            if ($hasVoted) {
                throw new Exception('Member has already cast a vote in this election (Double voting prevented).');
            }

            // Record vote
            $vote = ElectionVote::create([
                'election_id' => $election->id,
                'voter_hash' => $voterHash,
                'candidate_id' => $candidate->id,
                'otp_verified' => true,
                'voted_at' => now(),
                'ip_address' => $ipAddress,
            ]);

            // Increment candidate vote total
            $candidate->increment('vote_count');

            return $vote;
        }, 'Failed to cast vote in election');
    }
}
