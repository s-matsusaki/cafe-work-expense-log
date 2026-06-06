import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';

function getCurrentMonth() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');

    return `${year}-${month}`;
}

function MonthlyReportApp() {
    const [month, setMonth] = useState(getCurrentMonth());
    const [report, setReport] = useState(null);
    const [loading, setLoading] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    useEffect(() => {
        async function fetchReport() {
            setLoading(true);
            setErrorMessage('');

            try {
                const response = await fetch(`/api/reports/monthly?month=${month}`, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('月次分析レポートの取得に失敗しました。');
                }

                const data = await response.json();

                setReport(data);
            } catch (error) {
                setErrorMessage(error.message);
                setReport(null);
            } finally {
                setLoading(false);
            }
        }

        fetchReport();
    }, [month]);

    return (
        <div className="space-y-6">
            <div className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <label htmlFor="report-month" className="block text-sm font-semibold text-slate-700">
                    対象月
                </label>

                <input
                    id="report-month"
                    type="month"
                    value={month}
                    onChange={(event) => setMonth(event.target.value)}
                    className="mt-2 rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
            </div>

            {loading && (
                <div className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p className="text-sm text-slate-600">読み込み中です...</p>
                </div>
            )}

            {errorMessage && (
                <div className="rounded-lg border border-red-200 bg-red-50 p-4">
                    <p className="text-sm text-red-700">{errorMessage}</p>
                </div>
            )}

            {report && (
                <div className="grid gap-4 md:grid-cols-3">
                    <div className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">作業時間合計</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">
                            {report.summary.total_work_hours} 時間
                        </p>
                        <p className="mt-1 text-xs text-slate-500">
                            {report.summary.total_work_minutes} 分
                        </p>
                    </div>

                    <div className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">支出合計</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">
                            {report.summary.total_expense_amount.toLocaleString()} 円
                        </p>
                    </div>

                    <div className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">会計ソフト未記録</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">
                            {report.summary.unrecorded_expense_count} 件
                        </p>
                    </div>
                </div>
            )}
        </div>
    );
}

const element = document.getElementById('monthly-report-app');

if (element) {
    createRoot(element).render(<MonthlyReportApp />);
}
