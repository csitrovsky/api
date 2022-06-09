<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Enum;

final class DeliverySchema
{
    /** @var string Fulfilled by Seller */
    const FBS = 'fbs';

    /** @var string Fulfilled by Ozon */
    const FBO = 'fbo';

    /** @var string */
    const CROSSBORDER = 'crossborder';
}
