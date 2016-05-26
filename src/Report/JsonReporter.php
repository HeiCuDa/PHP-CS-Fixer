<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Report;

/**
 * @author Boris Gorbylev <ekho@ekho.name>
 *
 * @internal
 */
final class JsonReporter implements ReporterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function generate(ReportSummary $reportSummary)
    {
        $jFiles = array();

        foreach ($reportSummary->getChanged() as $file => $fixResult) {
            $jfile = array('name' => $file);

            if ($reportSummary->shouldAddAppliedFixers()) {
                $jfile['appliedFixers'] = $fixResult['appliedFixers'];
            }

            if (!empty($fixResult['diff'])) {
                $jfile['diff'] = $fixResult['diff'];
            }

            $jFiles[] = $jfile;
        }

        $json = array(
            'files' => $jFiles,
        );

        if (null !== $reportSummary->getTime()) {
            $json['time'] = array(
                'total' => round($reportSummary->getTime() / 1000, 3),
            );
        }

        if (null !== $reportSummary->getMemory()) {
            $json['memory'] = round($reportSummary->getMemory() / 1024 / 1024, 3);
        }

        return json_encode($json);
    }
}