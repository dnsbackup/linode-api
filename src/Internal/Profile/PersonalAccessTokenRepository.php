<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2018 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
//----------------------------------------------------------------------

namespace Linode\Internal\Profile;

use Linode\Entity\Entity;
use Linode\Entity\Profile\PersonalAccessToken;
use Linode\Internal\AbstractRepository;
use Linode\Repository\Profile\PersonalAccessTokenRepositoryInterface;

/**
 * {@inheritdoc}
 */
class PersonalAccessTokenRepository extends AbstractRepository implements PersonalAccessTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $parameters): PersonalAccessToken
    {
        $this->checkParametersSupport($parameters);

        $response = $this->client->api($this->client::REQUEST_POST, $this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new PersonalAccessToken($this->client, $json);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $parameters): PersonalAccessToken
    {
        $this->checkParametersSupport($parameters);

        $response = $this->client->api($this->client::REQUEST_PUT, sprintf('%s/%s', $this->getBaseUri(), $id), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new PersonalAccessToken($this->client, $json);
    }

    /**
     * {@inheritdoc}
     */
    public function revoke(int $id): void
    {
        $this->client->api($this->client::REQUEST_DELETE, sprintf('%s/%s', $this->getBaseUri(), $id));
    }

    /**
     * {@inheritdoc}
     */
    protected function getBaseUri(): string
    {
        return '/profile/tokens';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedFields(): array
    {
        return [
            PersonalAccessToken::FIELD_ID,
            PersonalAccessToken::FIELD_LABEL,
            PersonalAccessToken::FIELD_SCOPES,
            PersonalAccessToken::FIELD_CREATED,
            PersonalAccessToken::FIELD_TOKEN,
            PersonalAccessToken::FIELD_EXPIRY,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function jsonToEntity(array $json): Entity
    {
        return new PersonalAccessToken($this->client, $json);
    }
}
